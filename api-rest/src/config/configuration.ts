import dotenv from 'dotenv';

dotenv.config();

interface IConfiguration {
  SOAP_URL: string;
  PORT: number;
}

export const CONFIGURATION: IConfiguration = {
  SOAP_URL: process.env.SOAP_URL || '',
  PORT: +process.env.PORT! || 3000,
};