<div>
    {{-- In work, do what you enjoy. --}}
    <form action="{{ $this->presignedUrl }}" method="post" enctype="multipart/form-data">
        <input wire:model="file" type="file" name="file">
        <button type="submit">Upload</button>
    </form>
</div>
