## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2024-05-14 - Accessible Icon Buttons in Task Modal
**Learning:** Using raw SVG icons (like `<Trash2 />`) with `@click` handlers directly on them prevents keyboard navigation and lacks screen reader context. The `cursor-pointer text-error` classes are insufficient for full accessibility.
**Action:** Wrapped the delete icon in the task modal within a native `<button type="button">`. Added `aria-label="Delete task"` for screen readers and `title="Delete task"` for tooltips. Added `hover:bg-error/10` for visual feedback and `focus-visible:ring-2 focus-visible:ring-current` to ensure keyboard focus is clearly visible.
