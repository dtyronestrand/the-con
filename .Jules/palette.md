## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2024-06-03 - Keyboard Accessibility in Custom Elements
**Learning:** Custom pseudo-buttons (`<p role="button">`) and styled checkboxes (`display: none` on `<input>`) break keyboard accessibility natively.
**Action:** Always add `tabindex="0"` and keyboard event handlers to pseudo-buttons, and use visually hidden techniques (`opacity: 0; position: absolute;`) instead of `display: none` for native inputs behind custom styles.
