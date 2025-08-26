@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="py-5 bg-light text-center">
        <div class="container">
            <h1 class="display-5 fw-bold">Welcome to ShopnoStore</h1>
            <p class="lead mb-4">Manage your stores, products, sales, and reports from a single platform.</p>
            <a href="{{ url('/userLogin') }}" class="btn btn-primary btn-lg">Start Selling</a>
        </div>
    </section>

<section class="py-5">
    <div class="container">
        <div class="row g-4 d-flex justify-content-between">

            <!-- First Card: About Shopno -->
            <div class="col-md-5">
                <div class="card shadow-sm h-100 bg-dark text-light">
                    <div class="card-body">
                        <h4 class="card-title text-white text-center">Welcome to Shopno</h4>
                        <p class="card-text">
                            Shopno is a multi-store inventory management system that helps you
                            manage multiple outlets across different cities with ease.
                        </p>

                    </div>
                </div>
            </div>

            <!-- Second Card: Our Policy -->
            <div class="col-md-5">
                <div class="card shadow-sm h-100 bg-dark text-light">
                    <div class="card-body">
                        <h4 class="card-title text-white text-center">Our Policy</h4>
                        <p class="card-text">
                            We ensure transparency in inventory, secure transactions, and real-time
                            sales tracking. Our goal is to empower storekeepers with reliable tools
                            for stock management and customer satisfaction.
                        </p>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>



    <!-- Footer -->
    <footer class="py-4 bg-dark text-light text-center mt-3">
        <div class="container">
            &copy; {{ date('Y') }} Multi Store Inventory. All rights reserved.
        </div>
    </footer>
@endsection
