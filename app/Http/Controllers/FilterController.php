<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Shipment;
use App\User;

class FilterController extends Controller
{
	public function filterShipment(Request $request)
	{
		// return $request->all();
		// dd($start = $request->no_btw['start'] - 1);
		$start = $request->no_btw['start'] - 1;
		if (Auth::user()->hasRole('Admin')) {
			if ($request->selectCountry['id'] == 'all') {
				if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
					if ($request->select['id'] == 'all') {
						if ($request->selectStatus['name'] == 'All') {
				// return 'st6';
							return Shipment::latest()->skip($start)->take(500)->get();
						} else {
				// return 'st5';
							return Shipment::where('status', $request->selectStatus['name'])->latest()->skip($start)->take(500)->get();
						}

					} else {
						if ($request->selectStatus['name'] == 'All') {
						// return 'st6';
							return Shipment::latest()->skip($start)->where('branch_id', $request->select['id'])->take(500)->get();
						} else {
						// return 'st5';
							return Shipment::where('status', $request->selectStatus['name'])->where('branch_id', $request->select['id'])->latest()->skip($start)->take(500)->get();
						}
				// return 'st4';
					// return Shipment::where('branch_id', $request->select['id'])->latest()->skip($start)->take(500)->get();
					}
				} else {
					if ($request->select['id'] == 'all') {
						if ($request->selectStatus['name'] == 'All') {
				// return 'st1';
							return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->latest()->skip($start)->take(500)->get();
						} else {
				// return 'st2';
							return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->latest()->skip($start)->take(500)->where('status', $request->selectStatus['name'])->get();
						}
					} else {
						if ($request->selectStatus['name'] == 'All') {
				// return 'st1';
							return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->latest()->skip($start)->take(500)->get();
						} else {
				// return 'st2';
							return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->latest()->skip($start)->take(500)->where('status', $request->selectStatus['name'])->get();
						}
					}
				}
			} else {

				if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
					if ($request->select['id'] == 'all') {
						if ($request->selectStatus['name'] == 'All') {
				// return 'st6';
							return Shipment::latest()->skip($start)->where('country_id', $request->selectCountry['id'])->take(500)->get();
						} else {
				// return 'st5';
							return Shipment::where('status', $request->selectStatus['name'])->latest()->skip($start)->where('country_id', $request->selectCountry['id'])->take(500)->get();
						}

					} else {
				// return 'st4';
						return Shipment::where('branch_id', $request->select['id'])->latest()->skip($start)->where('country_id', $request->selectCountry['id'])->take(500)->get();
					}
				} else {
					if ($request->select['id'] == 'all') {
						if ($request->selectStatus['name'] == 'All') {
				// return 'st1';
							return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->latest()->skip($start)->take(500)->get();
						} else {
				// return 'st2';
							return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->latest()->skip($start)->take(500)->where('status', $request->selectStatus['name'])->get();
						}
					} else {
						if ($request->selectStatus['name'] == 'All') {
				// return 'st1';
							return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->latest()->skip($start)->take(500)->get();
						} else {
				// return 'st2';
							return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->latest()->skip($start)->take(500)->where('status', $request->selectStatus['name'])->get();
						}
					}
				}
			}

		} elseif (Auth::user()->hasPermissionTo('Filter Shipments')) {
		// return $request->all();
			if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
				if ($request->select['id'] == 'all') {
					if ($request->selectStatus['name'] == 'All') {
				// return 'st6';
						return Shipment::latest()->skip($start)->where('country_id', Auth::user()->country_id)->take(500)->get();
					} else {
				// return 'st5';
						return Shipment::where('status', $request->selectStatus['name'])->where('country_id', Auth::user()->country_id)->latest()->skip($start)->take(500)->get();
					}

				} else {
					if ($request->selectStatus['name'] == 'All') {
						// return 'st6';
						return Shipment::latest()->skip($start)->where('branch_id', $request->select['id'])->where('country_id', Auth::user()->country_id)->where('country_id', Auth::user()->country_id)->take(500)->get();
					} else {
						// return 'st5';
						return Shipment::where('status', $request->selectStatus['name'])->where('branch_id', $request->select['id'])->where('country_id', Auth::user()->country_id)->where('country_id', Auth::user()->country_id)->latest()->skip($start)->take(500)->get();
					}
				// return 'st4';
					// return Shipment::where('branch_id', $request->select['id'])->where('country_id', Auth::user()->country_id)->latest()->skip($start)->take(500)->get();
				}
			} else {
				if ($request->select['id'] == 'all') {
					if ($request->selectStatus['name'] == 'All') {
				// return 'st1';
						return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', Auth::user()->country_id)->latest()->skip($start)->take(500)->get();
					} else {
				// return 'st2';
						return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', Auth::user()->country_id)->latest()->skip($start)->take(500)->where('status', $request->selectStatus['name'])->get();
					}
				} else {
					if ($request->selectStatus['name'] == 'All') {
				// return 'st1';
						return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', Auth::user()->country_id)->where('branch_id', $request->select['id'])->latest()->skip($start)->take(500)->get();
					} else {
				// return 'st2';
						return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', Auth::user()->country_id)->where('branch_id', $request->select['id'])->latest()->skip($start)->take(500)->where('status', $request->selectStatus['name'])->get();
					}
				}
			}
		}
	}

	public function getDeriveredS(Request $request)
	{
		if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
			if ($request->select['id'] == 'all') {
				if ($request->selectStatus['name'] == 'All') {
				// return 'st6';
					return Shipment::where('status', 'Delivered')->count();
				} else {
				// return 'st5';
					return Shipment::where('status', 'Delivered')->count();
				}

			} else {
				// return 'st4';
				return Shipment::where('status', 'Delivered')->where('branch_id', $request->select['id'])->count();
			}
		} else {
			if ($request->select['id'] == 'all') {
				if ($request->selectStatus['name'] == 'All') {
				// return 'st1';
					return Shipment::where('status', 'Delivered')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->count();
				} else {
				// return 'st2';
					return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('status', 'Delivered')->count();
				}
			} else {
				if ($request->selectStatus['name'] == 'All') {
				// return 'st1';
					return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('status', 'Delivered')->count();
				} else {
				// return 'st2';
					return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('status', 'Delivered')->count();
				}
				// return 'st3';
				// return Shipment::where('branch_id', $request->select['id'])
				// 				// ->where('status', 'Delivered')
				// 				->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])
				// 				->count();
			}
		}
	}

	public function getOrdersS(Request $request)
	{
		// return $request->all();
		if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
			if ($request->select['id'] == 'all') {
				if ($request->selectStatus['name'] == 'All') {
				// return 'st6';
					return Shipment::count();
				} else {
				// return 'st5';
					return Shipment::count();
				}

			} else {
				// return 'st4';
				return Shipment::where('branch_id', $request->select['id'])->count();
			}
		} else {
			if ($request->select['id'] == 'all') {
				if ($request->selectStatus['name'] == 'All') {
				// return 'st1';
					return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->count();
				} else {
				// return 'st2';
					return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->count();
				}
			} else {
				if ($request->selectStatus['name'] == 'All') {
				// return 'st1';
					return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->count();
				} else {
				// return 'st2';
					return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->count();
				}
				// return Shipment::where('branch_id', $request->select['id'])
				// 				// 
				// 				->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])
				// 				->count();
			}
		}
	}

	public function getPendingS(Request $request)
	{
		if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
			if ($request->select['id'] == 'all') {
				if ($request->selectStatus['name'] == 'All') {
				// return 'st6';
					return Shipment::where('status', '!=', 'Delivered')->count();
				} else {
				// return 'st5';
					return Shipment::where('status', '!=', 'Delivered')->count();
				}

			} else {
				// return 'st4';
				return Shipment::where('status', '!=', 'Delivered')->where('branch_id', $request->select['id'])->count();
			}
		} else {
			if ($request->select['id'] == 'all') {
				if ($request->selectStatus['name'] == 'All') {
				// return 'st1';
					return Shipment::where('status', '!=', 'Delivered')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->count();
				} else {
				// return 'st2';
					return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('status', '!=', 'Delivered')->count();
				}
			} else {
				if ($request->selectStatus['name'] == 'All') {
				// return 'st1';
					return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('status', '!=', 'Delivered')->count();
				} else {
				// return 'st2';
					return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('status', '!=', 'Delivered')->count();
				}
				// return 'st3';
				// return Shipment::where('branch_id', $request->select['id'])
				// 				// ->where('status', '!=', 'Delivered')
				// 				->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])
				// 				->count();
			}
		}
	}

	public function filterPayment(Request $request)
	{
		if ($request->abbr == 'All') {
			$usersAll = User::latest()->take(200)->get();
			$userArr = [];
			foreach ($usersAll as $userAll) {
				if ($userAll->hasRole('Client')) {
					$userArr[] = $userAll;
				}
			}
			return $userArr;
		} else {
			$users = User::latest()->take(200)->where('payment_status', $request->abbr)->get();
			// return $user->hasRole('Client');
			$userArr = [];
			foreach ($users as $user) {
				if ($user->hasRole('Client')) {
					$userArr[] = $user;
				}
			}
			return $userArr;
		}

	}

	public function getreturned(Request $request)
	{
		if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
			if ($request->select['id'] == 'all') {
				if ($request->selectStatus['name'] == 'All') {
				// return 'st6';
					return Shipment::where('status', 'Returned')->count();
				} else {
				// return 'st5';
					return Shipment::where('status', 'Returned')->count();
				}

			} else {
				// return 'st4';
				return Shipment::where('status', 'Returned')->where('branch_id', $request->select['id'])->count();
			}
		} else {
			if ($request->select['id'] == 'all') {
				if ($request->selectStatus['name'] == 'All') {
				// return 'st1';
					return Shipment::where('status', 'Returned')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->count();
				} else {
				// return 'st2';
					return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('status', 'Returned')->count();
				}
			} else {
				if ($request->selectStatus['name'] == 'All') {
				// return 'st1';
					return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('status', 'Returned')->count();
				} else {
				// return 'st2';
					return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('status', 'Returned')->count();
				}
				// return 'st3';
				// return Shipment::where('branch_id', $request->select['id'])
				// 				// ->where('status', 'Returned')
				// 				->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])
				// 				->count();
			}
		}
	}


	public function btwSTdate(Request $request)
	{
		return $request->all();
	}

	public function getShipmentsBtw(Request $request)
	{
		return $request->all();
	}

}
