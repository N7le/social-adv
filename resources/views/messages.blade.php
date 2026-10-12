    @extends('layouts.app')

    @section('title', 'Messages · YouBee Social')
    @section('description', 'Read and send messages on YouBee Social.')
    @section('activePage', 'messages')

    @section('content')
      <main class="col-12 col-lg-8 col-xl-9" id="main">
        <h1 class="visually-hidden">Messages</h1>

        <livewire:chat />
      </main>
    @endsection
