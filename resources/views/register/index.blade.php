<x-login-register-layout>
    <x-slot:title>Registration</x-slot:title>

    <style>
        .form-registration {
            max-width: 500px;
            padding: 2rem;
            margin: auto;
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .form-registration h1 {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            text-align: center;
        }

        .form-registration .form-floating {
            margin-bottom: 1rem;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 0.75rem;
            border-radius: 0.25rem;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .text-body-secondary {
            color: #6c757d;
            text-align: center;
            margin-top: 1.5rem;
        }

        @media (min-width: 768px) {
            .form-signin {
                max-width: 500px;
            }
        }
    </style>

    <main class="form-registration w-100 m-auto">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <h1 class="h3 mb-3 fw-normal">Please register</h1>
                <form action="/register" method="post">
                    @csrf
                    <div class="form-floating">
                        <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                        <label for="name"></label>
                    </div>
                    <div class="form-floating">
                        <input type="email" name="email" class="form-control" id="email" placeholder="Email">
                        <label for="email"></label>
                    </div>
                    <div class="form-floating">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                        <label for="password"></label>
                    </div>
                    <button class="w-100 btn btn-lg btn-primary" type="submit">Register</button>
                </form>
                <small class="d-block text-center mt-3">Sudah terdaftar? <a href="/login" style="color: #007bff;">Login Disini!</a></small>
            </div>
        </div>
    </main>
</x-layout>
