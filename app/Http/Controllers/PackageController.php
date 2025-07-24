<?php

namespace App\Http\Controllers;

use App\Http\Requests\PackageRequest;
use App\Models\Package;
use App\Models\PackageFile;
use App\Models\PackageItem;
use App\Models\User;
use App\Repositories\PackageFileRepository;
use App\Repositories\PackageItemRepository;
use App\Repositories\PackageRepository;
use App\Traits\CommonTrait;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Redirect;

class PackageController extends Controller
{
    use CommonTrait;
    protected $packageRepository, $packageItemRepository, $packageFileRepository;

    public function __construct(PackageRepository $packageRepository, PackageItemRepository $packageItemRepository, PackageFileRepository $packageFileRepository)
    {
        $this->packageRepository = $packageRepository;
        $this->packageItemRepository = $packageItemRepository;
        $this->packageFileRepository = $packageFileRepository;
    }
    public function index()
    {
        $packages = $this->packageRepository->packages();
        return Inertia::render('Package/Report', ['packages' => $packages]);
    }

    public function create()
    {
        $users = User::where('is_active', 1)->where('type', User::USER_TYPE_CUSTOMER)->get();
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
                    $items = $this->stringifyToArray($request->items);
                    if (count($items) > 0) {
                        $this->packageItemRepository->insert($items, $package);
                    }
                }
                if ($request->hasFile('files')) {
                    $files = $request->file('files');
                    $this->packageFileRepository->insert($files, $package);
                }
            }
            DB::commit();
            return Redirect::route('admin.packages')->with('alert', 'Package added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors(['message' => $e->getMessage()]);
        }
    }


}
