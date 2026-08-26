@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>مرحباً بك في نظام GAREH</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-header">عدد العمّال</div>
                                <div class="card-body">
                                    <h1 class="card-title">{{ $workersCount ?? 0 }}</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-header">طلبيات اليوم</div>
                                <div class="card-body">
                                    <h1 class="card-title">{{ $ordersToday ?? 0 }}</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-info mb-3">
                                <div class="card-header">مبيعات اليوم</div>
                                <div class="card-body">
                                    <h1 class="card-title">{{ number_format($salesToday ?? 0) }} دج</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-warning mb-3">
                                <div class="card-header">تكاليف اليوم</div>
                                <div class="card-body">
                                    <h1 class="card-title">{{ number_format($costsToday ?? 0) }} دج</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection