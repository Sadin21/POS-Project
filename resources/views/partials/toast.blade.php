<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // didOpen:(toast)=>{toast.addEventListener('mouseenter', Swal.stopTimer);toast.addEventListener('mouseleave', Swal.resumeTimer);}
    const Toast = Swal.mixin({toast:true,position:'top-end',showConfirmButton:false,timer:5000,timerProgressBar:true});

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            Toast.fire({ icon: 'error', title: '{{ $error }}' });
        @endforeach
    @endif

    @if ($message = Session::get('error'))
        Toast.fire({ icon: 'error', title: '{{ $message }}' });
    @endif

    @if ($message = Session::get('success'))
        Toast.fire({ icon: 'success', title: '{{ $message }}' });
    @endif

    @if ($message = Session::get('warning'))
        Toast.fire({ icon: 'warning', title: '{{ $message }}' });
    @endif
</script>
