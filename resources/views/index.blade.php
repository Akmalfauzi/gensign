<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.2/lightgallery.min.js"
        integrity="sha512-jEJ0OA9fwz5wUn6rVfGhAXiiCSGrjYCwtQRUwI/wRGEuWRZxrnxoeDoNc+Pnhx8qwKVHs2BRQrVR9RE6T4UHBg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @include('layouts._header')
    <main>
        @yield('content')
        <p class="text-red-500">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit.
            Dignissimos hic facere excepturi. Ea laborum veritatis eum in
            sed laboriosam eveniet voluptas rem sequi animi odio laudantium
            earum ullam modi, officiis debitis voluptate, corrupti quis. Qui
            nostrum similique, dolorem laudantium magni voluptates eligendi,
            tempore porro veritatis dicta corrupti, ipsam enim quia
            blanditiis. Ipsam commodi possimus nisi itaque placeat cumque
            voluptates porro ipsa amet voluptatum deleniti, laboriosam ullam
            nostrum. Dolore expedita quas explicabo quibusdam veritatis sit
            debitis sapiente, eius in reprehenderit odit, sint fugiat
            doloremque tenetur id numquam corrupti consequatur cumque?
            Mollitia nisi quia eligendi at. Quas vitae beatae ab et impedit!
        </p>
        <img src="{{ asset('/design/assets/images/cover.jpg') }}" alt="Cover">
    </main>
    @include('layouts._footer')
</body>

</html>
