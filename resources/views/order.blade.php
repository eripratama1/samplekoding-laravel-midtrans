<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel workshop : payment gateway midtrans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="my-4">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Form input</div>
                        <div class="card-body">
                            <form action="" method="POST">
                                @csrf
                                <div class="form-group my-3">
                                    <label for="">name</label>
                                    <input type="text" name="name" class="form-control" id="">
                                </div>
                                <div class="form-group my-3">
                                    <label for="">qty</label>
                                    <input type="number" name="qty" class="form-control" id="">
                                </div>
                                <div class="form-group my-3">
                                    <label for="">price</label>
                                    <input type="number" name="price" class="form-control" id="">
                                </div>
                                <div class="form-group my-3">
                                    <label for="">Grand total</label>
                                    <input type="number" name="grand_total" class="form-control" id="">
                                </div>

                                <div class="form-group my-3">
                                    <button class="btn btn-primary" type="submit">Pay</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    Table here
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
