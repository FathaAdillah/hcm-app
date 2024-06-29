@extends('layouts.app')

@section('title', 'General Dashboard')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
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
                                    <input type="text" class="form-control" placeholder="Month" id="month"
                                        name="month">
                                </div>
                                <div class="form-group mr-md-2">
                                    <input type="text" class="form-control" placeholder="Year" id="year"
                                        name="year">
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                            </form>
                            <div class="ml-md-auto mt-3 mt-md-0">
                                <a href="#" class="btn btn-icon btn-icon-left btn-primary" data-toggle="modal"
                                    data-target="#checkinModal">
                                    <i class="fas fa-right-to-bracket"></i> Absen Masuk
                                </a>
                                <a href="#" class="btn btn-icon btn-icon-left btn-danger" data-toggle="modal"
                                    data-target="#checkoutModal">
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
                                    <th>Jam Pulang</th>
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
        </section>

        <div class="modal fade" id="checkinModal" tabindex="-1" role="dialog" aria-labelledby="checkinModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="checkinModalLabel">Check-In</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="mapCheckin" style="height: 300px;"></div>
                        <p>Live Time: <span id="liveTimeCheckin"></span></p>
                        <button id="recordCheckin" class="btn btn-primary">Record Check-In</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout Modal -->
        <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="checkoutModalLabel">Check-Out</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="mapCheckout" style="height: 300px;"></div>
                        <p>Live Time: <span id="liveTimeCheckout"></span></p>
                        <button id="recordCheckout" class="btn btn-primary">Record Check-Out</button>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        let checkinRecorded = false;
        let employeeId;

        $(document).ready(function() {
            // Set employeeId saat halaman dimuat
            employeeId = {{ auth()->user()->employees_id ?? 'null' }};
        });

        function updateLiveTime() {
            const now = new Date();
            document.getElementById('liveTimeCheckin').textContent = now.toLocaleTimeString();
            document.getElementById('liveTimeCheckout').textContent = now.toLocaleTimeString();
        }

        setInterval(updateLiveTime, 1000);

        let mapCheckin, mapCheckout, markerCheckin, markerCheckout;

        function initializeMap(mapId, markerVar, mapVar) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    mapVar = L.map(mapId).setView([lat, lng], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(mapVar);
                    markerVar = L.marker([lat, lng]).addTo(mapVar);
                    mapVar.on('locationfound', function(e) {
                        const newLat = e.latlng.lat;
                        const newLng = e.latlng.lng;
                        markerVar.setLatLng(e.latlng);
                    });
                    mapVar.locate({
                        setView: true,
                        watch: true
                    });
                }, function(error) {
                    alert('Error occurred. Error code: ' + error.code);
                });
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }

        $('#checkinModal').on('shown.bs.modal', function() {
            initializeMap('mapCheckin', markerCheckin, mapCheckin);
        });

        $('#checkoutModal').on('shown.bs.modal', function() {
            if (checkinRecorded) {
                initializeMap('mapCheckout', markerCheckout, mapCheckout);
            } else {
                alert('You must check-in before you can check-out.');
                $('#checkoutModal').modal('hide');
            }
        });

        function formatTime(date) {
            let hours = date.getHours();
            let minutes = date.getMinutes();
            let seconds = date.getSeconds();
            if (hours < 10) hours = '0' + hours;
            if (minutes < 10) minutes = '0' + minutes;
            if (seconds < 10) seconds = '0' + seconds;
            return `${hours}:${minutes}:${seconds}`;
        }

        // Assuming you have the token stored in a variable `authToken`
        const authToken = localStorage.getItem('authToken');

        function checkin(position, time) {
            const data = {
                date: new Date().toISOString().split('T')[0],
                check_in: time,
                latlong_in: `${position.coords.latitude}, ${position.coords.longitude}`,
                employees_id: employeeId
            };

            $.ajax({
                url: '/api/absen/checkin',
                type: 'POST',
                headers: {
                    'Authorization': `Bearer ${authToken}`
                },
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: function(response) {
                    checkinRecorded = true;
                    alert('Check-In recorded successfully.');
                    $('#checkinModal').modal('hide');
                },
                error: function(error) {
                    alert('Error recording Check-In.');
                }
            });
        }

        function checkout(position, time) {
            const data = {
                date: new Date().toISOString().split('T')[0],
                check_out: time,
                latlong_out: `${position.coords.latitude}, ${position.coords.longitude}`,
                employees_id: employeeId
            };

            $.ajax({
                url: '/api/absen/checkout',
                type: 'POST',
                headers: {
                    'Authorization': `Bearer ${authToken}`
                },
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: function(response) {
                    alert('Check-Out recorded successfully.');
                    $('#checkoutModal').modal('hide');
                },
                error: function(error) {
                    alert('Error recording Check-Out.');
                }
            });
        }

        $('#recordCheckin').on('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const time_in = formatTime(new Date());
                    checkin(position, time_in);
                });
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        });

        $('#recordCheckout').on('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const time_out = formatTime(new Date());
                    checkout(position, time_out);
                });
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        });
    </script>
@endpush
