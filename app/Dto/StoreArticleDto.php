<?php declare(strict_types=1);

namespace App\Dto;

use Illuminate\Http\Request;
use Symfony\Component\Uid\Uuid;

final class StoreArticleDto
{
    public ?Uuid $id;
    public ?string $title;
    public ?string $text;
    public ?Uuid $authUserIdentifier;

    public function __construct(
        ?Uuid $id,
        ?string $title,
        ?string $text,
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->text = $text;
    }

    public static function fromRequest(Request $request): StoreArticleDto
    {
        return new self(
            $request->get('id'),
            $request->get('title'),
            $request->get('text')
        );
    }
}
