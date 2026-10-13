@extends('omney-safari.layout')

@section('title', 'Create Your Account')

@section('content')
<style>
    .auth-page{position:relative;overflow:hidden;min-height:calc(100vh - 158px);padding:64px 0;background:radial-gradient(circle at 8% 20%,rgba(217,164,65,.14),transparent 27%),radial-gradient(circle at 92% 80%,rgba(47,125,84,.12),transparent 28%),#f4f1e9}.auth-page:before{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(18,55,42,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(18,55,42,.025) 1px,transparent 1px);background-size:36px 36px;mask-image:linear-gradient(to bottom,black,transparent)}.auth-shell{position:relative;z-index:1;display:grid;max-width:1080px;min-height:660px;grid-template-columns:minmax(0,.92fr) minmax(480px,1.08fr);overflow:hidden;border:1px solid rgba(18,55,42,.1);border-radius:30px;background:#fff;box-shadow:0 34px 90px rgba(18,55,42,.16)}.auth-story{position:relative;display:flex;min-height:660px;flex-direction:column;justify-content:space-between;padding:46px;color:#fff;background:linear-gradient(180deg,rgba(8,35,26,.2),rgba(8,35,26,.94)),url('https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1100&q=86') center/cover}.auth-story:after{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(217,164,65,.18),transparent 42%);pointer-events:none}.story-top,.story-bottom{position:relative;z-index:1}.story-badge{display:inline-flex;align-items:center;gap:9px;padding:9px 13px;border:1px solid rgba(255,255,255,.28);border-radius:999px;background:rgba(255,255,255,.1);backdrop-filter:blur(8px);font-size:.72rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.story-badge i{color:#f3c86f}.story-bottom h2{max-width:390px;margin:0 0 16px;font-family:'Playfair Display',serif;font-size:clamp(2.2rem,4vw,3.45rem);line-height:1.04}.story-bottom>p{max-width:390px;margin:0 0 28px;color:#dce9e2;font-size:.93rem;line-height:1.7}.story-benefits{display:grid;gap:13px}.story-benefit{display:flex;align-items:center;gap:12px;color:#edf5f0;font-size:.78rem;font-weight:700}.story-benefit i{display:grid;width:34px;height:34px;flex:0 0 34px;place-items:center;border-radius:50%;background:rgba(255,255,255,.12);color:#f3c86f}.auth-form-side{display:flex;align-items:center;padding:50px 54px}.auth-form-wrap{width:100%;max-width:460px;margin:auto}.auth-kicker{display:flex;align-items:center;gap:10px;color:var(--leaf);font-size:.71rem;font-weight:900;letter-spacing:.13em;text-transform:uppercase}.auth-kicker:before{content:'';width:28px;height:2px;background:var(--gold)}.auth-form-wrap h1{margin:13px 0 10px;color:var(--forest);font-family:'Playfair Display',serif;font-size:clamp(2.25rem,4vw,3.15rem);line-height:1.04}.auth-intro{margin:0 0 28px;color:var(--muted);font-size:.88rem;line-height:1.65}.auth-alert{display:flex;gap:10px;align-items:flex-start;margin-bottom:20px}.auth-form{display:grid;gap:17px}.auth-field{display:grid;gap:7px}.auth-field label{color:#243a2f;font-size:.78rem;font-weight:800}.auth-field input{width:100%;height:54px;padding:0 15px;border:1px solid #ced9d2;border-radius:13px;background:#fbfdfb;color:var(--ink);font:inherit;transition:.2s}.auth-field input:hover{border-color:#afc2b6}.auth-field input:focus{outline:0;border-color:var(--leaf);background:#fff;box-shadow:0 0 0 4px rgba(47,125,84,.1)}.auth-field input::placeholder{color:#9aa69f}.password-wrap{position:relative}.password-wrap input{padding-right:54px}.password-toggle{position:absolute;right:8px;top:7px;display:grid;width:40px;height:40px;place-items:center;border:0;border-radius:10px;background:transparent;color:#718078;cursor:pointer}.password-toggle:hover{background:#edf4ef;color:var(--leaf)}.password-hint{display:flex;align-items:center;gap:7px;color:var(--muted);font-size:.68rem}.password-hint i{color:var(--leaf)}.auth-submit{width:100%;min-height:54px;margin-top:4px;font-size:.84rem;box-shadow:0 11px 25px rgba(217,164,65,.28)}.auth-submit:hover{transform:translateY(-1px)}.auth-terms{margin:0;color:#7a8780;font-size:.66rem;line-height:1.55;text-align:center}.auth-divider{display:flex;align-items:center;gap:13px;margin:4px 0;color:#94a098;font-size:.67rem;font-weight:700}.auth-divider:before,.auth-divider:after{content:'';height:1px;flex:1;background:#e1e8e3}.signin-link{display:flex;min-height:50px;align-items:center;justify-content:center;gap:8px;border:1px solid #cdd9d1;border-radius:999px;color:var(--forest);font-size:.78rem;font-weight:800;transition:.2s}.signin-link:hover{border-color:var(--leaf);background:#f4f9f5;color:var(--leaf)}.secure-note{display:flex;align-items:center;justify-content:center;gap:7px;margin-top:20px;color:#7d8982;font-size:.65rem}.secure-note i{color:var(--leaf)}@media(max-width:900px){.auth-shell{max-width:620px;grid-template-columns:1fr}.auth-story{min-height:340px;padding:34px}.story-bottom h2{font-size:2.35rem}.story-bottom>p{margin-bottom:18px}.story-benefits{grid-template-columns:repeat(3,1fr)}.story-benefit{align-items:flex-start;flex-direction:column;gap:7px;font-size:.68rem}.auth-form-side{padding:44px}}@media(max-width:560px){.auth-page{padding:24px 0}.auth-page .container{width:min(100% - 24px,1160px)}.auth-shell{border-radius:22px}.auth-story{min-height:290px;padding:25px}.story-top{margin-bottom:60px}.story-bottom h2{font-size:2rem}.story-bottom>p,.story-benefits{display:none}.auth-form-side{padding:32px 22px}.auth-form-wrap h1{font-size:2.2rem}}
</style>

<main class="auth-page">
    <div class="container">
        <section class="auth-shell" aria-labelledby="register-title">
            <div class="auth-story">
                <div class="story-top"><span class="story-badge"><i class="fa-solid fa-compass"></i> Your journey starts here</span></div>
                <div class="story-bottom">
                    <h2>More than a safari. Your Uganda story.</h2>
                    <p>Create your traveller profile and keep every detail of your Omney Safari journey organised in one secure place.</p>
                    <div class="story-benefits">
                        <div class="story-benefit"><i class="fa-solid fa-calendar-check"></i><span>Track safari bookings and travel dates</span></div>
                        <div class="story-benefit"><i class="fa-solid fa-bell"></i><span>Receive important booking updates</span></div>
                        <div class="story-benefit"><i class="fa-solid fa-headset"></i><span>Stay connected with our local team</span></div>
                    </div>
                </div>
            </div>

            <div class="auth-form-side">
                <div class="auth-form-wrap">
                    <span class="auth-kicker">Join Omney Safari</span>
                    <h1 id="register-title">Create your account</h1>
                    <p class="auth-intro">Save your traveller details, manage your bookings, and prepare for an unforgettable Uganda experience.</p>

                    @if ($errors->any())
                        <div class="alert alert-error auth-alert" role="alert"><i class="fa-solid fa-circle-exclamation"></i><span>{{ $errors->first() }}</span></div>
                    @endif

                    <form method="POST" action="{{ route('register.store') }}" class="auth-form">
                        @csrf
                        <div class="auth-field"><label for="name">Full name</label><input id="name" name="name" value="{{ old('name') }}" autocomplete="name" placeholder="Enter your full name" required autofocus></div>
                        <div class="auth-field"><label for="email">Email address</label><input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="you@example.com" required></div>
                        <div class="auth-field"><label for="password">Password</label><div class="password-wrap"><input id="password" type="password" name="password" autocomplete="new-password" placeholder="Create a secure password" minlength="8" required><button class="password-toggle" type="button" data-password-toggle="password" aria-label="Show password"><i class="fa-regular fa-eye"></i></button></div><span class="password-hint"><i class="fa-solid fa-shield-halved"></i> Use at least 8 characters.</span></div>
                        <div class="auth-field"><label for="password_confirmation">Confirm password</label><div class="password-wrap"><input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" placeholder="Enter your password again" minlength="8" required><button class="password-toggle" type="button" data-password-toggle="password_confirmation" aria-label="Show confirmed password"><i class="fa-regular fa-eye"></i></button></div></div>
                        <button class="btn auth-submit" type="submit"><i class="fa-solid fa-user-plus"></i> Create my account</button>
                        <p class="auth-terms">By creating an account, you agree to provide accurate traveller information and use Omney Safari responsibly.</p>
                        <div class="auth-divider"><span>Already travelling with us?</span></div>
                        <a class="signin-link" href="{{ route('login') }}"><i class="fa-solid fa-arrow-right-to-bracket"></i> Sign in to your account</a>
                    </form>

                    <div class="secure-note"><i class="fa-solid fa-lock"></i> Your personal information is securely protected.</div>
                </div>
            </div>
        </section>
    </div>
</main>

<script>
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.passwordToggle);
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';
            button.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
            button.querySelector('i').className = isHidden ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
        });
    });
</script>
@endsection
