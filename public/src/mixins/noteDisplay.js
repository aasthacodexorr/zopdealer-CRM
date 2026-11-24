// Display-only helpers for the shared Notes tab design (avatars, file icons).
// No data or API behaviour lives here.
export default {
  computed: {
    noteComposerName() {
      const fromUsers = this.note_author ? this.note_author(this.app_metadata && this.app_metadata.id) : "";
      return (
        fromUsers ||
        (window.global && window.global.user_metadata ? window.global.user_metadata.name : "")
      );
    },
  },
  methods: {
    noteInitials(name) {
      const parts = String(name || "")
        .trim()
        .split(/\s+/)
        .filter((part) => part);
      const letters = (parts[0] || "").charAt(0) + (parts[1] || "").charAt(0);
      return letters ? letters.toUpperCase() : "?";
    },
    noteAvatarStyle(name) {
      const palette = [
        ["#e6efff", "#2f62d6"],
        ["#e3f6e8", "#1e8a43"],
        ["#fff3d1", "#a26a00"],
        ["#efe8ff", "#6b46c1"],
        ["#def6f3", "#11806f"],
        ["#fde6ef", "#b83268"],
      ];
      const value = String(name || "");
      let hash = 0;
      for (let i = 0; i < value.length; i++) {
        hash = (hash * 31 + value.charCodeAt(i)) >>> 0;
      }
      const [background, color] = palette[hash % palette.length];
      return { background, color };
    },
    noteFileIcon(name) {
      const ext = String(name || "").split("?")[0].split(".").pop().toLowerCase();
      if (["png", "jpg", "jpeg", "gif", "webp", "svg", "heic"].includes(ext)) return "bx-image";
      if (ext === "pdf") return "bxs-file-pdf";
      if (["doc", "docx", "txt", "rtf"].includes(ext)) return "bx-file";
      if (["xls", "xlsx", "csv"].includes(ext)) return "bx-spreadsheet";
      if (["zip", "rar", "7z"].includes(ext)) return "bx-archive";
      return "bx-paperclip";
    },
  },
};
