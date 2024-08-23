<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        navigator.serviceWorker.register("{{ asset('service-worker.js') }}");

        function askForPermission() {
            Notification.requestPermission().then((permission) => {
                if (permission === 'granted') {
                    navigator.serviceWorker.ready.then((sw) => {
                        sw.pushManager.subscribe({
                            userVisibleOnly: true,
                            applicationServerKey: "BPK--sF6CtKR7m2jAA4ofZdmsSHi1nxcm3TGfepChwOYA2oE26P50f6hWnYdyFD5zaibY3gB6ExD0VGXUkgyBoc"
                        }).then((subscription) => {
                            console.log(subscription);
                            saveSub(JSON.stringify(subscription));
                        })
                    })
                }
            })
        }

        function saveSub(sub) {
            $.ajax({
                type: "POST",
                url: "{{ route('saveSubscription') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'sub': sub
                },
                success: function(data) {
                    console.log(data);
                }
            })
        }

        function sendNotification() {
            $.ajax({
                type: "POST",
                url: "{{ route('sendNotification') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'name': $('#name').val(),
                    'body': $('#body').val(),
                },
                success: function(data) {
                    console.log(data);
                }
            })
        }
    </script>
</body>

</html>

{{-- Public Key:
BPK--sF6CtKR7m2jAA4ofZdmsSHi1nxcm3TGfepChwOYA2oE26P50f6hWnYdyFD5zaibY3gB6ExD0VGXUkgyBoc 
Private Key:
eqmkimjoeQbFGOA3OjwUviXvwQzK1Mys1t0zAib8A0s --}}