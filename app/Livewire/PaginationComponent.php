<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class PaginationComponent extends Component
{
    use WithPagination;

    public $perPage = 12;
    public $search = '';
    public $category = '';
    public $author = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'author' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingAuthor()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function updatingSortDirection()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.pagination-component');
    }
}
