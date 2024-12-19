window.Pan = {
    track: function(elementId) {
        fetch('/pan/track', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                element: elementId,
                url: window.location.pathname
            })
        }).catch(console.error);
    }
}; 