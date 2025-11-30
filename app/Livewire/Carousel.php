<?php

namespace App\Livewire;

use Livewire\Component;
use Statamic\Facades\Entry as EntryFacade;

class Carousel extends Component
{
    public array $slides = [];
    public array $computedSlides = [];

    public int $current = 0;
    public ?int $previous = null;

    public string $animationMode = 'fade'; // <- NEW
    public string $direction = 'right';

    public array $gradientPool = [
        'bg-gradient-to-r from-blue-500 to-purple-600',
        'bg-gradient-to-r from-green-500 to-teal-600',
        'bg-gradient-to-r from-pink-500 to-red-600',
        'bg-gradient-to-r from-yellow-400 to-orange-500',
        'bg-gradient-to-r from-indigo-500 to-cyan-400',
    ];

    public function mount()
    {
        $this->slides = EntryFacade::query()
            ->where('collection', 'quotes')
            ->where('published', true)
            ->inRandomOrder()
            ->get()
            ->pluck('title')
            ->toArray();

        if (empty($this->slides)) {
            $this->slides = ['No slides found'];
        }

        $this->computeSlides();
    }

    /** Precompute gradients + slide metadata so Blade has zero logic */
    protected function computeSlides(): void
    {
        $this->computedSlides = [];

        foreach ($this->slides as $index => $text) {
            $this->computedSlides[] = [
                'index'    => $index,
                'title'    => $text,
                'gradient' => $this->gradientPool[$index % count($this->gradientPool)],
                'is_current' => $index === $this->current,
                'is_previous' => $index === $this->previous,
            ];
        }
    }

    /** Update computed slides after state change */
    protected function refreshComputed(): void
    {
        foreach ($this->computedSlides as $i => $slide) {
            $this->computedSlides[$i]['is_current']  = ($i === $this->current);
            $this->computedSlides[$i]['is_previous'] = ($i === $this->previous);
        }
    }

    public function next()
    {
        $this->direction = 'right';
        $this->previous  = $this->current;
        $this->current   = ($this->current + 1) % count($this->slides);

        $this->refreshComputed();
    }

    public function prev()
    {
        $this->direction = 'left';
        $this->previous  = $this->current;
        $this->current   = ($this->current - 1 + count($this->slides)) % count($this->slides);

        $this->refreshComputed();
    }

    public function goTo($index)
    {
        $index = (int) $index;

        if ($index === $this->current) return;

        $this->direction = $index > $this->current ? 'right' : 'left';

        $this->previous = $this->current;
        $this->current  = $index;

        $this->refreshComputed();
    }

    public function render()
    {
        return view('livewire.carousel');
    }
}
