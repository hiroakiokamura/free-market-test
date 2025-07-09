import './bootstrap';

// 画像プレビュー機能
document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    // プレビュー用のコンテナを作成
                    let previewContainer = document.getElementById('image-preview');
                    if (!previewContainer) {
                        previewContainer = document.createElement('div');
                        previewContainer.id = 'image-preview';
                        previewContainer.className = 'mt-4';
                        imageInput.parentElement.parentElement.appendChild(previewContainer);
                    }

                    // プレビュー画像を表示
                    previewContainer.innerHTML = `
                        <div class="relative">
                            <img src="${e.target.result}" class="max-h-48 mx-auto rounded-lg" alt="プレビュー">
                            <button type="button" class="absolute top-2 right-2 bg-gray-800 bg-opacity-50 text-white rounded-full p-1 hover:bg-opacity-75" onclick="removePreview()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

// プレビューを削除する関数
window.removePreview = function () {
    const previewContainer = document.getElementById('image-preview');
    if (previewContainer) {
        previewContainer.remove();
    }
    document.getElementById('image').value = '';
};

// 画像アップロード機能
document.addEventListener('DOMContentLoaded', function () {
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('image');
    const preview = document.getElementById('preview');
    const uploadPrompt = document.getElementById('uploadPrompt');
    const removeButton = document.getElementById('removeImage');

    if (dropArea && fileInput && preview && uploadPrompt && removeButton) {
        const previewImg = preview.querySelector('img');

        // ドラッグオーバー時のスタイル
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropArea.classList.add('bg-gray-50', 'border-red-500');
            });
        });

        // ドラッグリーブ時のスタイル
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropArea.classList.remove('bg-gray-50', 'border-red-500');
            });
        });

        // ドロップ時の処理
        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                handleFile(file);
            }
        });

        // ファイル選択時の処理
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });

        // 削除ボタンの処理
        removeButton.addEventListener('click', () => {
            fileInput.value = '';
            preview.classList.add('hidden');
            uploadPrompt.classList.remove('hidden');
            previewImg.src = '';
        });

        // ファイル処理の共通関数
        function handleFile(file) {
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                    uploadPrompt.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
    }
});
