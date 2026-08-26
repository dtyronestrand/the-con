## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2024-05-15 - Hiding Native Checkboxes Safely
**Learning:** Hiding native checkboxes with `display: none` completely removes them from the accessibility tree, making them invisible to screen readers and keyboard navigation, breaking core a11y requirements.
**Action:** Always hide native inputs visually instead of functionally. Use `opacity: 0; position: absolute; width: 1px; height: 1px;` to keep them focusable and perceivable, and then style the sibling elements using `:focus-visible`.
