<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Example;

class ExampleController extends Controller
{
    public function index()
    {
        $examples = Example::orderBy('created_at', 'desc');

        return view('example.index', [
            'examples' => $examples->paginate(10),
        ]);
    }

    public function show(Example $example)
    {
        return view('example.show', [
            'example' => $example,
        ]);
    }

    public function create()
    {
        return view('example.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateExample($request);

        $example = new Example($data);

        $example->save();

        return redirect(route('examples.show', ['example' => $example->id]));
    }

    public function edit(Example $example, Request $request)
    {
        return view('example.edit', [
            'example' => $example,
        ]);
    }

    public function update(Example $example, Request $request)
    {
        $data = $this->validateExample($request);

        $example->fill($data);
        $example->save();

        return redirect(route('examples.show', ['example' => $example->id]));
    }

    public function destroy(Example $example, Request $request)
    {
        if ($example->delete()) {
            $messageStatus = 'success';
            $request->session()->flash('message', 'Deleted example.');
        } else {
            $messageStatus = 'error';
            $request->session()->flash('message', 'An error occurred while trying to delete the example.');
        }

        $request->session()->flash('message-status', $messageStatus);

        return redirect(route('examples.index'));
    }

    public function validateExample(Request $request)
    {
        return $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
    }
}
