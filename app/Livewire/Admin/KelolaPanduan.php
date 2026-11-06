<?php

namespace App\Livewire\Admin;

use App\Models\Panduan;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Kelola Panduan & SOP Praktikum')]
class KelolaPanduan extends Component
{
    use WithPagination;

    public string $filterKategori = 'semua';

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    // Form fields
    public string $kategori = 'publik';

    public int $urutan = 1;

    public string $judul = '';

    public string $subjudul = '';

    public string $konten = '';

    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'kategori' => 'required|in:publik,internal',
            'urutan' => 'required|integer|min:1',
            'judul' => 'required|string|max:255',
            'subjudul' => 'nullable|string|max:255',
            'konten' => 'required|string',
            'is_active' => 'boolean',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterKategori(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['editingId', 'subjudul', 'judul', 'konten']);
        $this->kategori = ($this->filterKategori !== 'semua') ? $this->filterKategori : 'publik';
        $maxUrutan = Panduan::where('kategori', $this->kategori)->max('urutan') ?? 0;
        $this->urutan = $maxUrutan + 1;
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $panduan = Panduan::findOrFail($id);
        $this->editingId = $panduan->id;
        $this->kategori = $panduan->kategori;
        $this->urutan = $panduan->urutan;
        $this->judul = $panduan->judul;
        $this->subjudul = $panduan->subjudul ?? '';
        $this->konten = $panduan->konten;
        $this->is_active = (bool) $panduan->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId) {
            $panduan = Panduan::findOrFail($this->editingId);
            $panduan->update($validated);
            session()->flash('success', "Panduan '{$this->judul}' berhasil diperbarui.");
        } else {
            Panduan::create($validated);
            session()->flash('success', "Panduan '{$this->judul}' berhasil ditambahkan.");
        }

        $this->showModal = false;
        $this->reset(['editingId', 'subjudul', 'judul', 'konten']);
    }

    public function toggleActive(int $id): void
    {
        $panduan = Panduan::findOrFail($id);
        $panduan->is_active = ! $panduan->is_active;
        $panduan->save();

        session()->flash('success', "Status panduan '{$panduan->judul}' diubah menjadi ".($panduan->is_active ? 'Aktif' : 'Nonaktif').'.');
    }

    public function delete(int $id): void
    {
        $panduan = Panduan::findOrFail($id);
        $judul = $panduan->judul;
        $panduan->delete();

        session()->flash('success', "Panduan '{$judul}' berhasil dihapus.");
    }

    public function render()
    {
        $query = Panduan::query()
            ->when($this->filterKategori !== 'semua', fn ($q) => $q->where('kategori', $this->filterKategori))
            ->when($this->search, fn ($q) => $q->where(function ($sub) {
                $sub->where('judul', 'like', "%{$this->search}%")
                    ->orWhere('subjudul', 'like', "%{$this->search}%")
                    ->orWhere('konten', 'like', "%{$this->search}%");
            }))
            ->orderBy('kategori')
            ->orderBy('urutan');

        return view('livewire.admin.kelola-panduan', [
            'panduanList' => $query->paginate(10),
            'totalPublik' => Panduan::where('kategori', 'publik')->count(),
            'totalInternal' => Panduan::where('kategori', 'internal')->count(),
        ])->layout('components.layouts.admin', ['title' => 'Kelola Panduan & SOP']);
    }
}
