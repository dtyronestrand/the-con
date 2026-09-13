## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2024-05-18 - Accessible Checkboxes and Hover Reveal
**Learning:** Custom checkboxes using `display: none` completely remove the element from the accessibility tree, making keyboard navigation impossible. Additionally, reveal-on-hover patterns (`opacity-0 group-hover:opacity-100`) hide elements from keyboard users unless paired with focus states.
**Action:** Use `sr-only` and `peer` on hidden inputs, styling a sibling with `peer-focus-visible` for focus rings. Always pair `group-hover:opacity-100` with `group-focus-within:opacity-100` to ensure keyboard accessibility.
