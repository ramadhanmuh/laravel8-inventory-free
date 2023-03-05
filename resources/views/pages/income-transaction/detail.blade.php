@extends('layouts.dashboard')

@section('title', "$application->name - Transaksi Masuk - $item->reference_number")

@section('description', 'Halaman yang berisi informasi detail transaksi masuk.')

@section('route_name', 'Detail Transaksi Masuk')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="m-0 text-dark">
                {{ $item->reference_number }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4 col-lg-3">
                    <b>Pemasok <span class="d-md-none">:</span></b>
                </div>
                <div class="col-md-8 col-lg-9">
                    <b class="d-none d-md-inline">:</b> {{ $item->supplier }}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 col-lg-3">
                    <b>Catatan <span class="d-md-none">:</span></b>
                </div>
                <div class="col-md-8 col-lg-9">
                    <b class="d-none d-md-inline">:</b> {{ $item->remarks }}
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <b>Daftar Barang</b>
                    <div class="table-responsive mt-2">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="align-middle">No</th>
                                    <th class="align-middle text-left">Nomor Unik</th>
                                    <th class="align-middle text-left">Deskripsi</th>
                                    <th class="align-middle">Satuan</th>
                                    <th class="align-middle text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $number = 1;
                                    $itemTotal = 0;
                                @endphp
                                @foreach ($subitems as $incomeTransactionItem)
                                    <tr>
                                        <td class="align-middle">
                                            {{ $number }}
                                        </td>
                                        <td class="align-middle text-left">
                                            {{ $incomeTransactionItem->item->part_number }}
                                        </td>
                                        <td class="align-middle text-left">
                                            {{ $incomeTransactionItem->item->description }}
                                        </td>
                                        <td class="align-middle">
                                            {{ $incomeTransactionItem->item->unitOfMeasurement->full_name }}
                                        </td>
                                        <td class="align-middle text-right">
                                            {{ number_format($incomeTransactionItem->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @php
                                        $number++;
                                        $itemTotal += $incomeTransactionItem->amount;
                                    @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">
                                        Total Barang
                                    </th>
                                    <th class="text-right">
                                        {{ number_format($itemTotal, 0, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>
    </div>
@endsection