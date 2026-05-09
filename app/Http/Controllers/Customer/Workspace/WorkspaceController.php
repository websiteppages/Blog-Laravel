<?php

namespace App\Http\Controllers\Customer\Workspace;

use App\Http\Controllers\Controller;
use App\Http\Requests\Workspace\StoreWorkspaceRequest;
use App\Http\Requests\Workspace\UpdateWorkspaceRequest;
use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Enums\MemberStatus;
use App\Services\Customer\WorkspaceService;

class WorkspaceController extends Controller
{
    public function __construct(private WorkspaceService $workspaceService) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $workspaces = Workspace::where('owner_id', $user->id)
            ->orWhereHas('workspaceMembers', fn($q) =>
                $q->where('user_id', $user->id)->where('status', MemberStatus::Active->value)
            )
            ->withCount(['workspaceMembers' => fn($q) => $q->where('status', MemberStatus::Active->value)])
            ->get();

        return view('customer.workspace.index', compact('workspaces'));
    }

    public function create()
    {
        return view('customer.workspace.create');
    }

    public function store(StoreWorkspaceRequest $request)
    {
        $workspace = $this->workspaceService->create($request->user(), $request->validated());
        return redirect()->route('customer.workspaces.index')->with('success', "Workspace \"{$workspace->name}\" created successfully!");
    }

    public function show(Workspace $workspace)
    {
        $this->authorize('view', $workspace);
        return view('customer.workspace.show', compact('workspace'));
    }

    public function edit(Workspace $workspace)
    {
        $this->authorize('update', $workspace);
        return view('customer.workspace.edit', compact('workspace'));
    }

    public function update(UpdateWorkspaceRequest $request, Workspace $workspace)
    {
        $this->workspaceService->update($workspace, $request->validated(), $request->user());
        return redirect()->route('customer.workspaces.edit', $workspace)->with('success', 'Workspace updated.');
    }

    public function destroy(Request $request, Workspace $workspace)
    {
        $this->authorize('delete', $workspace);
        $this->workspaceService->delete($workspace, $request->user());
        return redirect()->route('customer.workspaces.index')->with('success', 'Workspace deleted.');
    }

    public function switch(Request $request, Workspace $workspace)
    {
        $this->workspaceService->switchWorkspace($request->user(), $workspace);
        return redirect()->route('customer.workspaces.index')->with('success', "Switched to \"{$workspace->name}\".");
    }
}
