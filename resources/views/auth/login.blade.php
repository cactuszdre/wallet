@extends('layouts.app')

@section('title', 'Connexion')

@push('styles')
<!-- optional styles specific to auth pages -->
@endpush

@section('content')
<div class="auth-container" style="max-width:420px;margin:4rem auto;padding:2rem;background:var(--bg-secondary);border-radius:12px;border:1px solid rgba(255,255,255,0.03);">
    <h1 style="margin-bottom:1rem">Se connecter</h1>

    @if ($errors->any())
        <div class="alert" style="background:#ffefef;color:#9b111e;padding:0.75rem;border-radius:6px;margin-bottom:1rem;">
            <ul style="margin:0;padding-left:1.25rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div style="margin-bottom:0.75rem;">
            <label for="email" style="display:block;font-size:0.875rem;color:var(--text-secondary);margin-bottom:0.25rem">Adresse e-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus style="width:100%;padding:0.5rem;border-radius:6px;border:1px solid rgba(0,0,0,0.1);background:var(--bg-primary);color:var(--text-primary);" />
        </div>

        <div style="margin-bottom:0.75rem;">
            <label for="password" style="display:block;font-size:0.875rem;color:var(--text-secondary);margin-bottom:0.25rem">Mot de passe</label>
            <input id="password" type="password" name="password" required style="width:100%;padding:0.5rem;border-radius:6px;border:1px solid rgba(0,0,0,0.1);background:var(--bg-primary);color:var(--text-primary);" />
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
            <label style="font-size:0.875rem;color:var(--text-secondary);"><input type="checkbox" name="remember"> Se souvenir</label>
            <a href="{{ route('password.request') }}" style="font-size:0.875rem;color:var(--primary);">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="btn-primary" style="width:100%;padding:0.75rem;border-radius:8px;background:linear-gradient(135deg,var(--gold) 0%,var(--gold-light) 100%);border:none;color:var(--bg-primary);font-weight:600;">Se connecter</button>
    </form>

    <p style="margin-top:1rem;color:var(--text-secondary);font-size:0.9rem">Pas encore de compte ? <a href="{{ route('register') }}" style="color:var(--primary);">S'inscrire</a></p>
</div>
@endsection
