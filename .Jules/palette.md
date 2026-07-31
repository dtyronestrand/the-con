## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2023-10-27 - Hidden Checkboxes Break Keyboard Accessibility
**Learning:** In custom checkbox components (like Checkmark.vue), using `display: none` on the native `<input type="checkbox">` removes it from the document flow, making it impossible for keyboard users to focus on it.
**Action:** When building custom styled inputs, use visually hidden properties (`opacity: 0; position: absolute; width: 1px; height: 1px;`) on the native input instead of `display: none`, and style adjacent sibling elements using the `:focus-visible` pseudo-class (e.g., `input:focus-visible ~ span`) to ensure visible focus rings.
