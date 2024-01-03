<html lang="en" dir="ltr">

<head>
    <base href="/" />

    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="viewport" content="viewport-fit=cover, width=device-width, initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="msapplication-tap-highlight" content="no" />

    <title>Masuk | Kasir</title>
    <meta name="application-name" content="AAB" />
    <meta name="description" content="AAB">
    <meta name="title" content="AAB">

    <meta name="theme-color" content="#33447b">

    <link rel="icon" type="image/x-icon" href="./favicon.ico">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,400;1,700&&amp;display=swap">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body class="d-flex flex-column min-vh-100" style="background: #308EE5">
    <header class="py-4">
        <div class="container-fluid">
            <a class="navbar-brand ms-5" href="#">
                <img src="{{ asset('assets/imgs/logo.png') }}" height="40" alt="FaaFoo Logo" loading="lazy" />
            </a>
        </div>
    </header>
    <div class="bg-white" style="">
        <div class="row m-auto" style="max-width:450px; height: 80vh">
            <div class="m-auto">
                <h1 class="text-center mb-5">Login</h1>
                <form action="{{ route('auth.login') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            placeholder="Enter Username">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Enter password">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3 ">Login</button>
                </form>
                <div class="d-flex justify-center w-full">
                    Lupa password?
                    <a href="#exampleModal" class="ms-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Reset Password
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('auth.reset') }}" method="POST" enctype="multipart/form-data" id="formReset">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="username" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip"
                                placeholder="Enter NIP">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="resetButton">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    @include('partials.toast')

    <script>

$(document).ready(function () {
        $("#resetButton").click(function () {
            // Disable the button to prevent multiple submissions
            $(this).prop("disabled", true);

            // Get form data
            var formData = $("#formReset").serialize();

            // Send AJAX request to reset API endpoint
            $.ajax({
                type: "POST",
                url: "{{ route('auth.reset') }}",
                data: formData,
                success: function (response) {
                    // Enable the button
                    $("#resetButton").prop("disabled", false);

                    // Show SweetAlert with the response message and new password
                    Swal.fire({
                        icon: 'success',
                        title: response.message,
                        html: `<p>Password baru: <span style="user-select: text;">${response.data}</span></p>`,
                        allowOutsideClick: true, // Allow outside click to close the modal
                    });
                },
                error: function (error) {
                    // Enable the button
                    $("#resetButton").prop("disabled", false);

                    // Show SweetAlert with the error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.responseJSON.error,
                        allowOutsideClick: true, // Allow outside click to close the modal
                    });
                },
            });
        });
    });



        // $buttonReset = document.getElementById('resetButton');
        // $form = document.getElementById('formReset');
        // console.log($buttonReset);
        // $buttonReset.addEventListener('click', function() {
        //     let data = $form.serialize();
        //     $.ajax({
        //         type: "method",
        //         url: "{{ route('auth.reset') }}",
        //         data: data,
        //         success: function(response) {
        //             Swal.fire({
        //                 icon: 'success',
        //                 title: 'Berhasil',
        //                 text: 'Silahkan cek email anda untuk mengganti password',
        //                 showConfirmButton: false,
        //                 timer: 1500
        //             })
        //         },
        //         error: function(response) {
        //             Swal.fire({
        //                 icon: 'error',
        //                 title: 'Gagal',
        //                 text: 'NIP tidak ditemukan',
        //                 showConfirmButton: false,
        //                 timer: 1500
        //             })

        //         }
        //     });
        // });

        // document.addEventListener('DOMContentLoaded', function() {
        //     document.querySelector('.form').addEventListener('submit', function(ev) {
        //         const btn = document.querySelector('.btn');

        //         btn.disabled = true;
        //         btn.classList.toggle('btn-loading');
        //     });
        // });
    </script>
</body>

</html>
