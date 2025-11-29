<?php

namespace App\Http\Controllers;

use App\Helpers\PackageStatus;
use App\Http\Requests\PackageRequest;
use App\Models\Package;
use App\Models\PackageItem;
use App\Models\PackageFile;
use App\Models\User;
use App\Notifications\UpdateStatusWithNoteNotification;
use App\Repositories\PackageFileRepository;
use App\Repositories\PackageInvoiceRepository;
use App\Repositories\PackageItemRepository;
use App\Repositories\PackageRepository;
use App\Repositories\UserRepository;
use App\Traits\CommonTrait;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Redirect;
use Response;

class PackageController extends Controller
{
    use CommonTrait;
    protected $packageRepository, $packageItemRepository, $packageFileRepository, $userRepository, $packageInvoiceRepository;

    public function __construct(PackageRepository $packageRepository, PackageItemRepository $packageItemRepository, PackageFileRepository $packageFileRepository, UserRepository $userRepository, PackageInvoiceRepository $packageInvoiceRepository)
    {
        $this->packageRepository = $packageRepository;
        $this->packageItemRepository = $packageItemRepository;
        $this->packageFileRepository = $packageFileRepository;
        $this->userRepository = $userRepository;
        $this->packageInvoiceRepository = $packageInvoiceRepository;
    }

    public function index(Request $request)
    {
        // Validate filter parameters
        $validated = $request->validate([
            'status' => 'nullable|integer|in:1,2,3,4',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'sender_id' => 'nullable|exists:users,id',
            'tracking_id' => 'nullable|string|max:255',
            'total_value_min' => 'nullable|numeric|min:0',
            'total_value_max' => 'nullable|numeric|min:0|gte:total_value_min',
        ]);

        // Get customers for sender dropdown
        $customers = $this->userRepository->customers();

        // Get packages with filters
        $packages = $this->packageRepository->packages($validated);

        return Inertia::render('Package/Report', [
            'packages' => $packages,
            'customers' => $customers,
            'filters' => $validated
        ]);
    }

    public function kanban()
    {
        $packages = $this->packageRepository->allPackages();
        return Inertia::render('Package/Kanban', ['packages' => $packages]);
    }

    public function create()
    {
        $users = User::where('type', User::USER_TYPE_CUSTOMER)->get();
        return Inertia::render('Package/Create', ['users' => $users]);
    }

    public function store(PackageRequest $request)
    {
        try {
            DB::beginTransaction();
            $request->merge([
                'package_id' => $this->generateRandomNumberFormat()
            ]);
            $package = $this->packageRepository->store($request->all());
            if ($package) {
                if ($request->items) {
                    $items = $request->items;

                    foreach ($items as $item) {
                        // Save item
                        $packageItem = $this->packageItemRepository->insertOne($item, $package);

                        if (isset($item['files']) && is_array($item['files'])) {
                            foreach ($item['files'] as $file) {
                                $path = $this->addFile($file, 'storage/app/public/package_items/');
                                $this->packageFileRepository->insertOne([
                                    'package_id' => $package->id,
                                    'package_item_id' => $packageItem->id,
                                    'name' => $file->getClientOriginalName(),
                                    'file' => $path,
                                ]);
                            }
                        }
                    }
                }

                if ($request->hasFile('files')) {
                    $files = $request->file('files');
                    $this->packageFileRepository->insert($files, $package);
                }
            }
            DB::commit();
            return Redirect::route('admin.packages')->with('alert', 'Package added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function edit(Package $package)
    {
        $package->load('files', 'items', 'invoices');
        $package->items->load('packageFiles');

        return Inertia::render('Package/EditTabs/Basic', [
            'package' => $package,
            'customers' => $this->userRepository->customers(),
        ]);
    }

    public function update(PackageRequest $request, Package $package)
    {
        DB::beginTransaction();

        try {
            $package->update([
                'from' => $request->from,
                'date_received' => $request->date_received,
                'sender_id' => $request->sender_id,
                'tracking_id' => $request->tracking_id,
                'total_value' => $request->total_value,
                'weight' => $request->weight,
                'status' => $request->status,
                'note' => $request->note,
            ]);

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('package_files', 'public');
                    $package->files()->create([
                        'name' => $file->getClientOriginalName(),
                        'file' => $path,
                    ]);
                }
            }

            $items = $request->input('items', []);
            $updatedItemIds = [];

            foreach ($items as $index => $itemData) {
                // Existing item
                if (!empty($itemData['id'])) {
                    $item = PackageItem::find($itemData['id']);
                    if (!$item) continue;

                    $item->update([
                        'title' => $itemData['title'],
                        'description' => $itemData['description'],
                        'item_note' => $itemData['item_note'],
                        'quantity' => $itemData['quantity'],
                        'value_per_unit' => $itemData['value_per_unit'],
                        'total_line_value' => $itemData['total_line_value'],
                        'total_line_weight' => $itemData['total_line_weight'],
                    ]);

                    $updatedItemIds[] = $item->id;

                    if (!empty($itemData['delete_file_ids'])) {
                        foreach ($itemData['delete_file_ids'] as $fileId) {
                            $file = PackageFile::find($fileId);
                            if ($file && Storage::exists($file->file)) {
                                Storage::delete($file->file);
                                $file->delete();
                            }
                        }
                    }

                    if ($request->hasFile("items.$index.new_files")) {
                        foreach ($request->file("items.$index.new_files") as $file) {
                            $path = $this->addFile($file, 'storage/app/public/package_items/');
                            $item->packageFiles()->create([
                                'name' => $file->getClientOriginalName(),
                                'file' => $path,
                            ]);
                        }
                    }
                } else {
                    $newItem = $package->items()->create([
                        'title' => $itemData['title'],
                        'description' => $itemData['description'],
                        'item_note' => $itemData['item_note'],
                        'quantity' => $itemData['quantity'],
                        'value_per_unit' => $itemData['value_per_unit'],
                        'total_line_value' => $itemData['total_line_value'],
                        'total_line_weight' => $itemData['total_line_weight'],
                    ]);

                    $updatedItemIds[] = $newItem->id;

                    if (!empty($itemData['new_files']) && is_array($itemData['new_files'])) {
                        foreach ($itemData['new_files'] as $file) {
                            $path = $this->addFile($file, 'storage/app/public/package_items/');
                            $newItem->packageFiles()->create([
                                'name' => $file->getClientOriginalName(),
                                'file' => $path,
                            ]);
                        }
                    }
                }
            }

            if (!empty($itemData['delete_file_ids']) && is_array($itemData['delete_file_ids'])) {
                foreach ($itemData['delete_file_ids'] as $fileId) {
                    $file = PackageFile::find($fileId);
                    if ($file) {
                        if (Storage::disk('public')->exists($file->file)) {
                            Storage::disk('public')->delete($file->file);
                        }
                        $file->delete();
                    }
                }
            }


            DB::commit();

            return redirect()->route('admin.packages')->with('alert', 'Package updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }
    }



    public function destroy(Package $package)
    {
        try {
            DB::beginTransaction();
            $isDelete = $this->packageRepository->deletePackage($package->id);
            if ($isDelete) {
                $this->packageItemRepository->itemsDeleteByPackageId($package->id);
                $this->packageFileRepository->deletePackageFilesByPackageId($package->id);
            }
            DB::commit();
            return Redirect::route('admin.packages')->with('alert', 'Package deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function getUserPackages(User $user)
    {
        $userPackages = $this->packageRepository->shipmentPackages($user->id, [PackageStatus::ACTION_REQUIRED, PackageStatus::IN_REVIEW, PackageStatus::READY_TO_SEND, PackageStatus::CONSOLIDATE]);
        return Inertia::render('Admin/Users/EditTabs/Packages', ['user' => $user, 'userPackages' => $userPackages]);
    }

    /**
     * Update package status via AJAX for Kanban board
     */
    public function updateStatus(Request $request, Package $package)
    {
        try {
            DB::beginTransaction();

            // Validate the request
            $request->validate([
                'status' => 'required|integer|in:1,2,3,4',
            ]);

            // Update the package status
            $package->update(['status' => $request->status]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Package status updated successfully',
                'package' => $package->fresh()->load('customer', 'items', 'files'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update package status: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateNote(Request $request, Package $package)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'note' => 'required|string|max:1000',
            ]);
            $package->update(['note' => $request->note]);
            $user = $package->customer;
            $user->notify(new UpdateStatusWithNoteNotification($request->input('note'), $package));
            DB::commit();
            return Redirect::back()->with('alert', 'Package note updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors(['message' => $e->getMessage()]);
        }
    }
}
