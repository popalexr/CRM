export interface InvoiceDetails {
  id: number;
  number: string;
  client_name: string;
  date: string;
  due_date: string;
  status: string;
  total: number;
  currency: string;
  products: Array<any>;
  storno?: any;
  sent: boolean;
  payments?: Array<any>;
  creditNote?: any;
  document?: any;
  client_address?: string;
  client_city?: string;
  client_country?: string;
  client_county?: string;
  client_vat_code?: string;
  client_email?: string;
  client_phone?: string;
  client_bank?: string;
  client_iban?: string;
  created_at?: string;
}