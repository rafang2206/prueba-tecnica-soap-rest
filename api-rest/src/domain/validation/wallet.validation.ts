import * as joi from 'joi';

export const walletBalanceSchema = joi.object({
  document: joi.string().required(),
  phone: joi.number().required(),
});

export const walletRechargeSchema = joi.object({
  document: joi.string().required(),
  phone: joi.number().positive().required(),
  amount: joi.number().positive().required(),
});