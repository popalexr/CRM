export const tabs = {
  invoice: 'Invoice',
  history: 'History',
  notes: 'Notes',
};

export const ui = {
  notDue: 'Not due',
  daysUntilDue: 'days until due',
  totalAmount: 'Total Amount',
  openAmount: 'Open Amount',
  vatAmount: 'VAT. Amount',
  dueDate: 'Due Date',
  paidOnLabel: 'Paid On',
  customerAvDelay: 'Customer av delay',
  payments: 'Payments',
  add: 'Add',
  paidOn: 'Paid on',
  noPayments: 'No payments yet.',
  addPayment: 'Add Payment',
  amount: 'Amount',
  cancel: 'Cancel',
  productsServices: 'Products / Services',
  noItems: 'No items.',
  subtotal: 'Subtotal:',
  discount: 'Discount:',
  vat: 'VAT:',
  total: 'Total:',
  paymentMethod: 'Payment method: Bank transfer',
  issuedBy: 'Issued by:',
  signature: 'Signature: ____________________',
  changeStyle: 'Change Style',
  historyContent: 'History content here...',
  notesContent: 'Notes content here...',
  voidInvoice: 'Void Invoice',
  changeStatus: 'Change Status',
  editInvoice: 'Edit Invoice',
};

export const getStatusBadgeVariant = (status: string) => {
  switch (status) {
    case 'draft':
      return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-200';
    case 'submitted':
      return 'bg-orange-500 text-white dark:bg-orange-600';
    case 'paid':
      return 'bg-green-500 text-white dark:bg-green-600';
    case 'not_paid':
      return 'bg-red-500 text-white dark:bg-red-600';
    case 'anulled':
      return 'bg-gray-200 text-gray-700 dark:bg-gray-300';
    case 'storno':
      return 'bg-gray-600 text-white dark:bg-gray-700';
    default:
      return 'bg-slate-200 text-slate-700 dark:bg-slate-300';
  }
}

export const parseStatus = (status: string) => {
  switch (status) {
    case 'draft':
      return 'Draft';
    case 'submitted':
      return 'Submitted';
    case 'paid':
      return 'Paid';
    case 'not_paid':
      return 'Not Paid';
    case 'storno':
      return 'Storno';
    case 'anulled':
      return 'Anulled';
    default:
      return status.charAt(0).toUpperCase() + status.slice(1);
  }
};

export const parseDate = (dateString: string) => {
  const date = new Date(dateString);
  return date.toLocaleDateString(undefined, {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit'
  });
};
