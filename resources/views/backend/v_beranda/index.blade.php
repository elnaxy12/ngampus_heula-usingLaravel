@extends('backend.v_layouts.app')
@section('content')
    <!-- contentAwal -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body border-top">
                    <h5 class="card-title"> {{ $judul }}</h5>
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading"> Selamat Datang, {{ Auth::user()->nama }}</h4>
                        Aplikasi Toko Online dengan hak akses yang anda miliki sebagai
                        <b>
                            @if (Auth::user()->role == 1)
                                Super Admin
                            @elseif(Auth::user()->role == 0)
                                Admin
                            @endif
                        </b>
                        ini adalah halaman utama dari aplikasi Web Programming. Studi Kasus
                        Toko Online.
                        <hr>
                        <p class="mb-0">Kuliah..? BSI Aja !!!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <!-- LEFT COLUMN -->
        <div class="col-lg-6">

            <!-- Latest Posts -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Latest Posts</h4>
                </div>

                <div class="comment-widgets scrollable">

                    <!-- Comment -->
                    <div class="d-flex flex-row comment-row m-t-0">
                        <div class="p-2">
                            <img src="{{ asset('storage/img-user/img-default.jpg') }}" width="50" class="rounded-circle">
                        </div>
                        <div class="comment-text w-100">
                            <h6 class="font-medium">James Anderson</h6>
                            <span class="d-block m-b-15">
                                Lorem Ipsum is simply dummy text of the printing industry.
                            </span>

                            <div class="comment-footer">
                                <span class="text-muted float-right">April 14, 2016</span>
                                <button class="btn btn-cyan btn-sm">Edit</button>
                                <button class="btn btn-success btn-sm">Publish</button>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </div>
                        </div>
                    </div>

                    <!-- Comment -->
                    <div class="d-flex flex-row comment-row">
                        <div class="p-2">
                            <img src="{{ asset('storage/img-user/img-default.jpg') }}" width="50" class="rounded-circle">
                        </div>
                        <div class="comment-text active w-100">
                            <h6 class="font-medium">Michael Jorden</h6>
                            <span class="d-block m-b-15">
                                Lorem Ipsum is simply dummy text of the printing industry.
                            </span>

                            <div class="comment-footer">
                                <span class="text-muted float-right">May 10, 2016</span>
                                <button class="btn btn-cyan btn-sm">Edit</button>
                                <button class="btn btn-success btn-sm">Publish</button>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- TODO LIST -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">To Do List</h4>

                    <div class="todo-widget scrollable" style="height:450px;">
                        <ul class="list-group todo-list">

                            <li class="list-group-item">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" id="todo1" class="custom-control-input">
                                    <label for="todo1" class="custom-control-label">
                                        Lorem Ipsum dummy text
                                        <span class="badge badge-danger float-right">Today</span>
                                    </label>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" id="todo2" class="custom-control-input">
                                    <label for="todo2" class="custom-control-label">
                                        Printing text example
                                        <span class="badge badge-primary float-right">1 week</span>
                                    </label>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>

            <!-- Progress -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Progress Box</h4>

                    <div class="m-t-20">
                        <div class="d-flex justify-content-between">
                            <span>81% Clicks</span>
                            <span>125</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width:81%"></div>
                        </div>
                    </div>

                    <div class="m-t-25">
                        <div class="d-flex justify-content-between">
                            <span>72% Unique Clicks</span>
                            <span>120</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width:72%"></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-lg-6">

            <!-- Chat -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Chat</h4>

                    <div class="chat-box scrollable" style="height:475px;">
                        <ul class="chat-list">

                            <li class="chat-item">
                                <div class="chat-img">
                                    <img src="{{ asset('storage/img-user/img-default.jpg') }}" width="50" class="rounded-circle">
                                </div>
                                <div class="chat-content">
                                    <h6>James Anderson</h6>
                                    <div class="box bg-light-info">
                                        Lorem Ipsum dummy text
                                    </div>
                                </div>
                                <div class="chat-time">10:56</div>
                            </li>

                            <li class="odd chat-item">
                                <div class="chat-content">
                                    <div class="box bg-light-inverse">
                                        I would love to join the team.
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>

                <div class="card-body border-top">
                    <div class="row">
                        <div class="col-9">
                            <textarea class="form-control border-0" placeholder="Type..."></textarea>
                        </div>
                        <div class="col-3 text-right">
                            <button class="btn btn-cyan btn-lg">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="card">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tab1">Tab1</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab2">Tab2</a>
                    </li>
                </ul>

                <div class="tab-content p-3">
                    <div class="tab-pane active" id="tab1">
                        <p>Content tab 1</p>
                    </div>
                    <div class="tab-pane" id="tab2">
                        <p>Content tab 2</p>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- contentAkhir -->
@endsection
