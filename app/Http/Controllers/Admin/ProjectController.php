<?php

namespace App\Http\Controllers\Admin;


use App\Models\Project;
use Illuminate\Support\Str;
//use App\Http\Controllers\Admin\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::orderByDesc('updated_at')->orderByDesc('created_at')->get();
        return view('admin.projects.index', compact('projects'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $project = new Project();
        $types = Type::select('label', 'id')->get();

        return view('admin.projects.create', compact('project', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {    
        $request->validate([
            'title' => 'required|string|min:5|max:50|unique:projects',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
            'type_id' => 'nullable|exists:types,id',

        ], [
            'title.required' => 'Il titolo è obbligatorio',
            'title.min' => 'Il titolo deve essere :min caratteri',
            'title.max' => 'Il titolo deve essere :max caratteri',
            'title.unique' => 'Non possono esistere due progetti con lo stesso titolo',
            'image.image' => 'Il file inserito non è un\'immagine',
            'image.mimes' => 'Le estensioni valide sono: .png, .jpg, .jpeg',
            'content.required' => 'Il contenuto è obbligatorio',
            'type_id.exists' => 'Categoria non valida',

        ]);

        $data = $request->all();

        $project = new Project();
        $project->fill($data);
        $project->slug = Str::slug($data['title']);

        if(Arr::exists($data, 'image')){
            $extension = $data['image']->extension();

            $image_url = Storage::putFile('project_images', $data['image'], "$project->slug.$extension");
            $project->image = $image_url;
        }

        $project->save();

        return to_route('admin.projects.show', $project)->with('message', 'Project creato con successo')->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $types = Type::select('label','id')->get();
        return view('admin.projects.edit', compact('project', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {

        $request->validate([
            'title' => ['required','string','min:5','max:50', Rule::unique('projects')->ignore($project->id)],
            'content' => 'required|string',
            'image' => 'nullable|image',
            'type_id' => 'nullable|exists:types,id',

        ], [
            'title.required' => 'Il titolo è obbligatorio',
            'title.min' => 'Il titolo deve essere :min caratteri',
            'title.max' => 'Il titolo deve essere :max caratteri',
            'title.unique' => 'Non possono esistere due progetti con lo stesso titolo',
            'image.image' => 'Il file inserito non è un\'immagine',
            'image.mimes' => 'Le estensioni valide sono: .png, .jpg, .jpeg',
            'content.required' => 'Il contenuto è obbligatorio',
            'type_id.exists' => 'Categoria non valida',

        ]);


        $data = $request->all();
        $project->fill($data);
        $project->slug = Str::slug($data['title']);

        if(Arr::exists($data, 'image')){
            if($project->image) Storage::delete($project->image);
            $extension = $data['image']->extension();

            $image_url = Storage::putFile('project_images', $data['image'], "{$data['slug']} .$extension");
            $project->image = $image_url;
        }

        $project->save();
        
        return to_route('admin.projects.show', $project)->with('message', 'Project modificato con successo')->with('type', 'success');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return to_route('admin.projects.index')->with('type', 'danger')->with('message', 'Post eliminato con successo');
    }
}
