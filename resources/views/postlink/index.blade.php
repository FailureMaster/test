<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>lading page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-7">
                <div class="card p-5 border-0" style="box-shadow: 0px 0px 5px 1px rgba(201,201,201,0.39);">
                    <div class="card-body">
                        <h4 class="text-center card-title mb-5">Lead Form</h4>
                        <form action="{{ route('postlinkstore') }}" method="post">
                            @csrf
                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label">First Name</label>
                                    <input class="form-control" type="text" name="firstname">
                                </div>
                                <div class="col">
                                    <label class="form-label">Last Name</label>
                                    <input class="form-control" type="text" name="lastname">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label">Email</label>
                                    <input class="form-control" type="text" name="email">
                                </div>
                                <div class="col">
                                    <label class="form-label">Phone</label>
                                    <input class="form-control" type="text" name="mobile">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label class="form-label">Country Code</label>
                                    <input class="form-control" type="text" name="country_code">
                                </div>
                                <div class="col">
                                    <label class="form-label">Account Type</label>
                                    <select class="form-select" name="account_type">
                                        <option value="demo" selected="">Demo</option>
                                        <option value="real">Real</option>
                                    </select></div>
                                <div class="col">
                                    <label class="form-label" name="">Lead Source</label>
                                    <select class="form-select" name="lead_source">
                                        <option value="google" selected="">Google</option>
                                        <option value="facebook">Facebook</option>
                                    </select>
                                </div>
                            </div>
                        
                    </div>
                    <input class="btn btn-primary" type="submit" />
                </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>