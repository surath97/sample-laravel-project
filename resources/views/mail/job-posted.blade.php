<h2 class="text-4xl">{{ $job->title }}</h2>

<p>Congrats..! Your job is now live in our website</p>

<p>
    <a href="{{ url('/jobs/' . $job->id) }}">View your job Listing</a>
</p>