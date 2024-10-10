@extends('admin.template.app')
@section('title', 'Dashboard')
@section('content')


   <!-- Main content -->
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-sm-6 col-12">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{$karyawanHadirCount}}</h3>

                        <p class="font-weight-bold">Karyawan Hadir</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{route('admin.monitoring.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
           
            <!-- ./col -->
            <div class="col-sm-6 col-12">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{$karyawanIzinCount}}</h3>

                        <p class="font-weight-bold">Karyawan Izin</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-document"></i>
                    </div>
                    <a href="{{route('admin.perizinan.index')}}" class="small-box-footer" data-toggle="modal" data-target="#modalIzin">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <!-- ./col -->
            <div class="col-sm-6 col-12">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{$karyawanSakitCount}}</h3>

                        <p class="font-weight-bold">Karyawan Sakit</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-sad"></i>
                    </div>
                    <a href="{{route('admin.perizinan.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <!-- ./col -->
            <div class="col-sm-6 col-12">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{$karyawanTelatCount}}</h3>

                        <p class="font-weight-bold" >Karyawan Telat</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-clock"></i>
                    </div>
                    <a href="{{route('admin.monitoring.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
          
            <!-- ./col -->
        </div>

    </div><!-- /.container-fluid -->
<!-- /.content -->    

{{-- Modal --}}

@endsection