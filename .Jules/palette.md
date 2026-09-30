## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2024-05-15 - Interactive Checkbox Accessibility
**Learning:** Using `display: none` on a native checkbox removes it from the accessibility tree, making it invisible to screen readers and impossible to focus with a keyboard. It's crucial to use `.sr-only` instead to visually hide it while maintaining accessibility.
**Action:** When creating custom checkboxes, always apply `.sr-only` to the native `<input type="checkbox">`, add an `aria-label`, and style the sibling element using `peer-focus-visible`.
