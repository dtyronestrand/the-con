## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2025-02-28 - Keyboard Accessibility in Checkbox Components
**Learning:** Using `display: none` on native form inputs completely removes them from the accessibility tree, making them unfocusable via keyboard navigation and invisible to screen readers, breaking semantic accessibility.
**Action:** Always use utility classes like Tailwind's `sr-only` combined with `peer` to visually hide the native input while keeping it accessible, and style a sibling element using `peer-focus-visible` to represent the focus state visually.
