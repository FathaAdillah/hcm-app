@extends('layouts.app')

@section('title', 'General Dashboard')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Absensi</h1>
            </div>
            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Aturan Absensi</h4>
                    </div>
                    <div class="card-body row">
                        <div class="col-lg-6 col-md-6">
                            <p>1. Absen masuk pukul 08.00 WIB</p>
                            <p>2. Absen pulang pukul 17.00 WIB</p>
                            <p>3. Jika terlambat, maka akan dikenakan denda</p>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <img src="{{ asset('img/time.jpg') }}" style="width: 90%" alt="time">
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <form class="form-inline mb-3 mb-md-0">
                                <div class="form-group mr-md-2">
                                    <input type="text" class="form-control" placeholder="Month" id="month" name="month">
                                </div>
                                <div class="form-group mr-md-2">
                                    <input type="text" class="form-control" placeholder="Year" id="year" name="year">
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                            </form>
                            <div class="ml-md-auto mt-3 mt-md-0">
                                <a href="" class="btn btn-icon btn-icon-left btn-primary">
                                    <i class="fas fa-right-to-bracket"></i> Absen Masuk
                                </a>
                                <a href="" class="btn btn-icon btn-icon-left btn-danger">
                                    <i class="fas fa-sign-out-alt"></i> Absen Pulang
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="float-right">
                            <form method="GET" action="">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search" name="name">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="clearfix mb-3"></div>
                        <div class="table-responsive">
                            <table class="table-striped table">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>jam Pulang</th>
                                    <th>Status</th>
                                    <th>Aktivitas</th>
                                </tr>
                                <tr>
                                    <td>12-05-2024</td>
                                    <td>08:10</td>
                                    <td>17:10</td>
                                    <td><span class="badge badge-danger">Terlambat</span></td>
                                    <td><button class="btn btn-success">Detail Aktivitas</button></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
    </div>
@endsection

@push('scripts')
@endpush
