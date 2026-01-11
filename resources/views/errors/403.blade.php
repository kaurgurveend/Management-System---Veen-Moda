@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <h1 class="display-5">403 — Akses Ditolak</h1>
    <p class="lead">Halaman ini khusus untuk Admin. Jika menurut Anda ini kesalahan, silakan hubungi pemilik akun.</p>
    <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">Kembali</a>
</div>
@endsection
