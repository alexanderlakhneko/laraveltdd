<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{
    /**
     * ThreadsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Channel $channel
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Channel $channel, Request $request)
    {
        if ($channel->exists) {
            $threads = $channel->threads()->latest();
        } else {
            $threads = Thread::latest();
        }

        if ($request->has('by')) {
            $user = User::whereName($request->input('by'))->firstOrFail();
            $threads->where('user_id', $user->id);
        }

        $threads = $threads->get();

        return view('threads.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'      => 'required',
            'body'       => 'required',
            'channel_id' => 'required|exists:channels,id',
        ]);

        $thread = Thread::create([
            'user_id'    => auth()->id(),
            'channel_id' => $request->input('channel_id'),
            'title'      => $request->input('title'),
            'body'       => $request->input('body'),
        ]);

        return redirect($thread->path());
    }

    /**
     * @param $channel
     * @param $thread
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($channel, Thread $thread)
    {
        return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        //
    }
}
