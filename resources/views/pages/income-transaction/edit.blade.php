@extends('layouts.dashboard')

@section('title', "$application->name - Transaksi (Masuk) - Ubah")

@section('description', 'Halaman yang berisi formulir untuk mengubah data transaksi pemasukkan.')

@section('route_name', 'Ubah Transaksi (Masuk)')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="m-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <ul class="nav nav-tabs bg-white" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Barang</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="selected-tab" data-toggle="tab" data-target="#selected" type="button" role="tab" aria-controls="selected" aria-selected="false">Terpilih</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Transaksi</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active bg-white border p-2 px-3" id="home" role="tabpanel">
            <form action="{{ route('income-transaction-items.update', $item->id) }}" method="post">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <select name="item_id" id="item_id" class="form-control">
                                <option value="">-- Pilih Barang --</option>
                                @foreach ($items as $value)
                                    <option value="{{ $value->id }}" {{ old('item_id') == $value->id ? 'selected' : '' }}>
                                        {{ $value->description }}
                                        ({{ $value->part_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="number" class="form-control" id="amount" name="amount" placeholder="Jumlah" value="{{ old('amount') }}" min="1">
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <a href="{{ route('income-transactions.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                        <button class="btn btn-primary" type="submit">
                            Tambah
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="tab-pane fade p-2 px-3 bg-white border" id="selected" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nomor Unik</th>
                            <th>Deskripsi</th>
                            <th>Satuan Barang</th>
                            <th class="text-center">Jumlah</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (empty(session('edit-income-transaction-item')))
                            @foreach ($item->incomeTransactionItems as $incomeTransactionItem)
                                <tr>
                                    <td class="align-middle">
                                        {{ $incomeTransactionItem->item->part_number }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $incomeTransactionItem->item->description }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $incomeTransactionItem->item->unitOfMeasurement->short_name }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ $incomeTransactionItem->amount }}
                                    </td>
                                    <td class="align-middle text-center">
                                        <form action="{{ url("income-transaction-items/$item->id/$incomeTransactionItem->item_id") }}" method="post" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            @forelse (session('edit-income-transaction-item')['incomeTransactionItems'] as $session)
                                <tr>
                                    <td class="align-middle">
                                        {{ $session['item']['part_number'] }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $session['item']['description'] }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $session['item']['unitOfMeasurement']['short_name'] }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ $session['amount'] }}
                                    </td>
                                    <td class="align-middle text-center">
                                        <form action="{{ url("income-transaction-items/$item->id/" . $session['item_id']) }}" method="post" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Barang belum dipilih.</td>
                                </tr>    
                            @endforelse
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="text-right">
                <a href="{{ route('income-transactions.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>
        </div>
        <div class="tab-pane fade p-2 px-3 bg-white border" id="profile" role="tabpanel">
            <form class="row" action="{{ route('income-transactions.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="html_created_at">Tanggal</label>
                        <input type="datetime-local"
                            class="form-control"
                            id="html_created_at"
                            name="html_created_at"
                            onkeyup="document.getElementById('created_at').value = +new Date(this.value) / 1000"
                            data-value="{{ empty(old('created_at')) ? $item->created_at : old('created_at') }}">
                        <input type="hidden"
                            name="created_at"
                            id="created_at"
                            value="{{ empty(old('created_at')) ? $item->created_at : old('created_at') }}">
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="reference_number">Nomor Referensi</label>
                        <input type="text"
                            name="reference_number"
                            id="reference_number"
                            value="{{ empty(old('reference_number')) ? $item->reference_number : old('reference_number') }}"
                            class="form-control">
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="supplier">Pemasok</label>
                        <input type="text"
                            class="form-control"
                            id="supplier"
                            name="supplier"
                            value="{{ empty(old('supplier')) ? $item->supplier : old('supplier') }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="remarks">Catatan</label>
                        <textarea name="remarks" id="remarks" class="form-control">{{ empty(old('remarks')) ? $item->remarks : old('remarks') }}</textarea>
                    </div>
                </div>
                <div class="col-12 text-right">
                    <a href="{{ route('income-transactions.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                    <button type="submit"
                        class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function datetimeLocal(unix) {
            var dt = new Date(unix * 1000);
            dt.setMinutes(dt.getMinutes() - dt.getTimezoneOffset());
            return dt.toISOString().slice(0, 16);
        }

        var htmlCreatedAt = document.getElementById('html_created_at'),
            unix = parseInt(htmlCreatedAt.getAttribute('data-value')),
            date = datetimeLocal(unix);

        htmlCreatedAt.value = date;
    </script>
@endsection