<html lang="en" dir="ltr">
    <head>
        <base href="/"/>
        
        <meta charset="utf-8">
        <meta name="robots" content="noindex, nofollow"> 
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="viewport" content="viewport-fit=cover, width=device-width, initial-scale=1.0"/>
        <meta name="format-detection" content="telephone=no"/>
        <meta name="msapplication-tap-highlight" content="no"/>
    
        <title>Ubah Kata Sandi | AAB Backoffice</title>
        <meta name="application-name" content="AAB"/>
        <meta name="description" content="AAB">
        <meta name="title" content="AAB">  
        
        <meta name="theme-color" content="#33447b">

        <link rel="icon" type="image/x-icon" href="./favicon.ico">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400;1,700&&amp;display=swap">
    
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    </head>
            
    <body class="d-flex flex-column min-vh-100">
        <nav class="navbar container d-flex justify-content-center pb-2 pt-4 mt-2">
            <img src="{{ asset('assets/img/logo.png') }}" alt="AAB Logo" width="auto" height="48px">
        </nav>

        <main class="pt-5 mt-1 container d-flex flex-column align-items-center flex-grow-1">
            <form class="card border-0 p-4 mb-0 form" style="width:90%;max-width:450px;" method="POST" action="">
                <h3 class="mb-4 pb-2">Ubah Kata Sandi</h3>

                @csrf
                <div class="mb-4 pt-1">
                    <label class="form-label" for="oldPassword">Kata Sandi Lama</label>
                    <input type="password" class="form-control" name="oldPassword" value="{{ old('oldPassword') }}" id="oldPassword" autocomplete="current-password" required>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="newPassword">Kata Sandi Baru</label>
                    <input type="password" class="form-control" name="newPassword" value="{{ old('newPassword') }}" id="newPassword" autocomplete="new-password" required>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="confirmPassword">Konfirmasi Kata Sandi</label>
                    <input type="password" class="form-control" name="confirmPassword" value="{{ old('confirmPassword') }}" id="confirmPassword" autocomplete="new-password" required>
                </div>

                <button type="submit" class="btn w-100 btn-primary fsemibold">Ubah</button>
            </form>

            <footer class="mt-auto mt-lg-5 pt-lg-3 pb-4 text-center">
                © {{ date('Y') }}, <b>PT. Angkasa Adibayu Buana</b>
            </footer>   
        </main>        
        
        @include('admin.partials.toast')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelector('.form').addEventListener('submit', function(ev) {
                    const btn = document.querySelector('.btn');
                    
                    btn.disabled = true;
                    btn.classList.toggle('btn-loading');
                });
            });
        </script>
    </body> 
</html>
