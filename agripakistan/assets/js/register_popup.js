function showRegisterPopup(role) {
    document.getElementById('registerPopup').style.display = 'flex';
    document.getElementById('role').value = role;
    document.getElementById('registerTitle').innerText = `Register as ${role.charAt(0).toUpperCase() + role.slice(1)}`;
}

function closeRegisterPopup() {
    document.getElementById('registerPopup').style.display = 'none';
}
