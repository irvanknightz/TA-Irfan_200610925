<x-login-register-layout>
  <x-slot:title>Login</x-slot:title>

  <style>
    .form-signin {
      max-width: 400px;
      /* Adjusted max width for better fit on large screens */
      padding: 2rem;
      margin: auto;
      background-color: #fff;
      /* White background for form */
      border-radius: 0.5rem;
      /* Rounded corners for the form */
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      /* Subtle shadow for the form */
    }

    .form-signin img {
      display: block;
      margin: 0 auto 1rem;
      /* Center the image and add margin */
    }

    .form-signin h1 {
      margin-bottom: 1.5rem;
      /* Increase margin below the heading */
      font-size: 1.5rem;
      /* Larger font size for the heading */
      text-align: center;
      /* Center the heading text */
    }

    .form-signin .form-floating {
      margin-bottom: 1rem;
      /* Increase spacing between fields */
    }

    .form-signin input[type="email"],
    .form-signin input[type="password"] {
      border-radius: 0.25rem;
      /* Rounded corners for inputs */
    }

    .btn-primary {
      background-color: #007bff;
      /* Primary button color */
      border: none;
      padding: 0.75rem;
      /* Padding for button */
      border-radius: 0.25rem;
      /* Rounded corners for button */
    }

    .btn-primary:hover {
      background-color: #0056b3;
      /* Darker shade on hover */
    }

    .form-check {
      margin-top: 1rem;
      /* Add spacing above checkbox */
    }

    .text-body-secondary {
      color: #6c757d;
      text-align: center;
      /* Center the copyright text */
      margin-top: 1.5rem;
      /* Margin above copyright text */
    }

    @media (min-width: 768px) {
      .form-signin {
        max-width: 500px;
        /* Increase max width on larger screens */
      }
    }

    .alert-success {
      background-color: #d4edda;
      /* Light green background */
      color: #155724;
      /* Dark green text */
      border-color: #c3e6cb;
      /* Light green border */
    }

    .invalid-feedback {
      display: block;
    }
  </style>

  <main class="form-signin w-100 m-auto">
    @if(session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session()->has('loginError'))
    <div class="alert alert-danger alert-dismissible fade show" style="color: #ff0000" role="alert">
      {{ session('loginError') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- <img class="mb-4" src="../assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> --}}
    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
    <form action="/login" method="post">
      @csrf
      <div class="form-floating">
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="name@example.com" autofocus required value="{{ old('email') }}">
        <label for="email"></label>
        @error('email')
        <div class="invalid-feedback" style="color: #ff0000;">
          {{ $message }}
        </div>
        @enderror
      </div>
      <div class="form-floating">
        <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
        <label for="password"></label>
      </div>
      <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
    </form>
    <small class="d-block text-center mt-3">Tidak terdaftar? <a href="/register" style="color: #007bff;">Daftar Disini!</a></small>
  </main>
  </x-layout>