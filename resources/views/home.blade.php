@extends('layouts.app')

@section('content')
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div> --}}



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create or Join Meeting</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-section {
            margin-bottom: 2rem;
        }
        .card-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- Header -->
        <header class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <h1>Meeting </h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <div class="row">
                <!-- Create Meeting Form -->
                <div class="col-md-6 form-section">
                    <div class="card">
                        <div class="card-header">
                            <h2>Create a Meeting</h2>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('create.meeting') }}">
                                @csrf
                                <!-- Meeting Title -->
                                <div class="mb-3">
                                    <label for="meetingTitle" class="form-label">Meeting Title</label>
                                    <input type="text" class="form-control" id="meetingTitle" name="title" required placeholder="Enter meeting title">
                                </div>

                                                               <!-- Save Button -->
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Create Meeting</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Join Meeting Form -->
                <div class="col-md-6 form-section">
                    <div class="card">
                        <div class="card-header">
                            <h2>Join a Meeting</h2>
                        </div>
                        <div class="card-body">
                            <form>
                                <!-- Meeting Link -->
                                <div class="mb-3">
                                    <label for="meetingLink" class="form-label">Meeting Link</label>
                                    <input type="url" class="form-control" id="meetingLink" placeholder="Enter the meeting link">
                                </div>

                                <!-- Join Button -->
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success">Join Meeting</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

       
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
</body>
</html>

@endsection
