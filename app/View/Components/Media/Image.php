<?php

namespace App\View\Components\Media;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Image extends Component
{
    public string $src;
    public string $alt;
    public ?int $width;
    public ?int $height;
    public string $fit;
    public int $quality;
    public array $formats;
    public string $class;
    public string $loading;
    public array $breakpoints;
    public array $sources = [];
    public string $fallbackUrl;

    public function __construct(
        string $src,
        string $alt = '',
        ?int $width = null,
        ?int $height = null,
        string $fit = 'crop',
        int $quality = 85,
        array $formats = ['avif', 'webp', 'jpg'],
        array $breakpoints = [],
        string $class = '',
        string $loading = 'lazy'
    ) {
        $this->src = $src;
        $this->alt = $alt;
        $this->width = $width;
        $this->height = $height;
        $this->fit = $fit;
        $this->quality = $quality;
        $this->formats = $formats;
        $this->breakpoints = $breakpoints;
        $this->class = $class;
        $this->loading = $loading;

        if (!empty($this->breakpoints)) {
            $this->buildResponsiveSources();
        } else {
            $this->buildSimpleSources();
        }
    }

    protected function buildResponsiveSources(): void
    {
        foreach ($this->breakpoints as $breakpoint) {
            $bpWidth = $breakpoint['width'] ?? $this->width;
            $bpHeight = $breakpoint['height'] ?? $this->height;
            $media = $breakpoint['media'] ?? null;

            foreach ($this->formats as $format) {
                $this->sources[] = [
                    'srcset' => $this->buildUrl($format, $bpWidth, $bpHeight),
                    'type' => $this->getMimeType($format),
                    'media' => $media,
                ];
            }
        }

        $lastBreakpoint = end($this->breakpoints);
        $this->fallbackUrl = $this->buildUrl('jpg', $lastBreakpoint['width'] ?? $this->width, $lastBreakpoint['height'] ?? $this->height);
    }

    protected function buildSimpleSources(): void
    {
        foreach ($this->formats as $format) {
            if ($format !== 'jpg' && $format !== 'jpeg') {
                $this->sources[] = [
                    'srcset' => $this->buildUrl($format),
                    'type' => $this->getMimeType($format),
                    'media' => null,
                ];
            }
        }

        $this->fallbackUrl = $this->buildUrl('jpg');
    }

    public function buildUrl(string $format = null, ?int $width = null, ?int $height = null): string
    {
        $params = [];
        $useWidth = $width ?? $this->width;
        $useHeight = $height ?? $this->height;

        if ($useWidth) {
            $params[] = 'w=' . $useWidth;
        }

        if ($useHeight) {
            $params[] = 'h=' . $useHeight;
        }

        if ($this->fit) {
            $params[] = 'fit=' . $this->fit;
        }

        if ($format) {
            $params[] = 'fm=' . $format;
        }

        $params[] = 'q=' . $this->quality;

        $queryString = implode('&', $params);

        return '/img/' . $this->src . ($queryString ? '?' . $queryString : '');
    }

    public function getMimeType(string $format): string
    {
        return match ($format) {
            'avif' => 'image/avif',
            'webp' => 'image/webp',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            default => 'image/jpeg',
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.media.image');
    }
}
