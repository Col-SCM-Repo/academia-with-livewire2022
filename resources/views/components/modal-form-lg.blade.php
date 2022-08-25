<div class="modal inmodal " id="{{ $idForm }}" tabindex="-1" role="dialog" wire:ignore.self aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content animated flipInY">
            <div class="ibox-title">
                <span>
                    <h5> {{ $titulo }} </h5>
                </span>
            </div>
            <div class="ibox-content"> {{ $slot }} </div>
        </div>
    </div>
</div>
