## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2026-05-06 - Custom Checkbox and Interactive Element Accessibility
**Learning:** Implementing custom visual checkboxes by hiding the native `<input>` with `display: none` breaks keyboard accessibility and hides them from screen readers. Using `<p role="button">` without `tabindex="0"` and explicit keyboard event handlers (`@keydown.enter`, `@keydown.space.prevent`) makes it impossible for keyboard users to interact with them.
**Action:** Hide native inputs visually using zero opacity and absolute positioning instead of `display: none`. Always ensure elements acting as buttons have `tabindex="0"`, handle appropriate keyboard events, and provide clear `focus-visible` styling.
