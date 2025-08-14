<?php

namespace App\Http\Controllers;

use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected $transactionRepository, $userRepository;

    public function __construct(TransactionRepository $transactionRepository, UserRepository $userRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $saleAmount = $this->transactionRepository->sumTotalTransaction();
        $customer = $this->userRepository->userCount();
        return Inertia::render('Admin/Dashboard/Report', ['saleAmount' => $saleAmount, 'totalCustomers' => $customer]);
    }
}
