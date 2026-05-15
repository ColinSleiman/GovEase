<!DOCTYPE html>
<html>
<body>
<h1>doc reader placeholder page</h1>

<hr>

<h2>Uploaded Documents</h2>

<table>
    <tr>
        <th>File Name</th>
        <th>Type</th>
        <th>Size</th>
        <th>Preview</th>
        <th>Download</th>
    </tr>

    @forelse ($documents as $document)
        <tr>
            <td>{{ $document['name'] }}</td>
            <td>{{ $document['type'] }}</td>
            <td>{{ $document['size'] }}</td>

            <td>
                @if ($document['canPreview'] == true)
                    @if (!empty($previewDocument) && $previewDocument['name'] == $document['name'])
                        <a href="{{ route('admin.document.reader') }}">
                            Close Preview
                        </a>
                    @else
                        <a href="{{ route('admin.document.reader', ['preview' => $document['name']]) }}">
                            Preview File
                        </a>
                    @endif
                @else
                    Download file to open
                @endif
            </td>

            <td>
                <a href="{{ route('admin.document.reader.download', $document['name']) }}">
                    Download
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5">No documents uploaded yet.</td>
        </tr>
    @endforelse
</table>

<hr>

<h2>Preview</h2>

@if (!empty($previewDocument))
    <h3>{{ $previewDocument['name'] }}</h3>

    @if ($previewDocument['extension'] == 'pdf')
        <iframe src="{{ route('admin.document.reader.preview', $previewDocument['name']) }}" width="100%" height="900"></iframe>
    @else
        <img src="{{ route('admin.document.reader.preview', $previewDocument['name']) }}" style="max-width: 100%; height: auto;">
    @endif
@endif
</body>
</html>
