## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2024-05-24 - Screen Reader Interpretation of Visual Text Symbols
**Learning:** Visual text symbols used as icons in buttons (such as '+', '✕', or '✎') are often interpreted literally by screen readers (e.g., reading out "plus sign" or "times"), which can be confusing when combined with the button's intended accessible name (like "Add new note").
**Action:** When implementing buttons that rely on text-based symbols for visual indication rather than SVG icons, wrap the text symbol in a `<span aria-hidden="true">` to prevent the screen reader from incorrectly announcing the character's literal name.
