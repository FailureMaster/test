@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="mb-1">
            <small>Note: Please download the CSV template, edit your data, and then upload the updated file. Thank you.</small>
        </div>
        <form
            method="POST"
            enctype="multipart/form-data"
            >
            @csrf
            <input type="file" name="filepond" >

        </form>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<a href="{{ route('admin.users.export') }}" class="btn btn-outline--primary addBtn h-45">
    <i class="las la-download"></i>@lang('Download CSV Template')
</a>
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
@push('script')
<script>
   
   const inputElement = document.querySelector('input[name="filepond"]');
    const pond = FilePond.create(inputElement, {
        credits: false  // Disable credits
    });

FilePond.setOptions({
    server: {
        process: {
            url: "{{ route('admin.users.import') }}",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            onload: (response) => {
                const res = JSON.parse(response);
                if (res.Success) {
                    Swal.fire({
                        title: 'Success',
                        text: 'Successfully imported ' + res.rowCount + ' rows.',
                        icon: 'success'
                    }).then(() => {
                        window.location.href = "{{ route('admin.users.all') }}";
                    });
                } else {
                    let errorMessage = '';
                    res.errors.forEach(error => {
                        errorMessage += error + '\n';
                    });

                    Swal.fire({
                        title: 'Error',
                        text: errorMessage,
                        icon: 'error'
                    });

                    // Show error message in FilePond
                    pond.removeFiles();
                    pond.setOptions({
                        labelIdle: 'Drag & Drop your file or <span class="filepond--label-action">Browse</span>',
                        labelFileProcessingComplete: 'Upload Error',
                    });
                }
            },
            onerror: (error) => {
                console.error('File upload error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'File upload failed. Please try again.',
                    icon: 'error'
                });

                // Show error message in FilePond
                pond.removeFiles();
                pond.setOptions({
                    labelIdle: 'Drag & Drop your file or <span class="filepond--label-action">Browse</span>',
                    labelFileProcessingComplete: 'Upload Error',
                });
            }
        }
    }
});


</script>
@endpush
